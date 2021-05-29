<?php

/*
 * Copyright 2015 Deutsches Forschungszentrum f�r K�nstliche Intelligenz
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 *
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Services;

use DFKI\ScorecardBundle\Entity\Issue;
use Doctrine\ORM\EntityManager;
use Exception;

class TypologyService {
	protected $em;
	public function __construct(EntityManager $entityManager) {
		$this->em = $entityManager;
	}
	
	/**
	 * import mqm issue definitions in php format
	 * will delete all issue definitions and create new issue definitions.
	 * therefore this comand will DELETE ALL PROJECTS!!!
	 */
	public function import($inputFile) {
		if (! file_exists ( $inputFile )) {
			return sprintf ( "Input file \"%s\" not found", $inputFile );
		} else {
			$connection = $this->em->getConnection ();
			$connection->query ( 'SET FOREIGN_KEY_CHECKS=0' );
			$this->em->createQuery ( "DELETE DFKIScorecardBundle:Issue i" )->execute ();
			$connection->query ( 'SET FOREIGN_KEY_CHECKS=1' );
			
			require_once ($inputFile);
			
			$ids = array_keys ( $issuenames );
			$map = array ();
			
			// import data
			foreach ( $ids as $id ) {
				$issue = new Issue ();
				$issue->setId ( $id );
				$issue->setName ( $issuenames [$id] );
				$issue->setDefinition ( $definitions [$id] );
				$issue->setNotes ( $notes [$id] );
				$issue->setExamples ( $examples [$id] );
				$this->em->persist ( $issue );
				$issue->setImported(0);
				$map [$id] = $issue;
			}
			
			// import parent / child relationships
			foreach ( $ids as $id ) {
				if ($parent [$id] != "" && $parent [$id] != "none") {
					$p = $map [$parent [$id]];
					$map [$id]->setParent ( $p );
					$this->em->persist ( $map [$id] );
				}
			}
			
			$this->em->flush ();
		}
	}
	

	
	/**
	 * generate an id for an imported issue
	 */
	private function generateIssueId($project) {
		$inc = 0;
		$id = null;
		while( $id == null){
			$query = $this->em->createQuery ( 'SELECT COUNT(i.id) FROM DFKIScorecardBundle:Issue i WHERE i.imported=true AND i.project=:project' )->setParameter ( "project", $project );
			$count = $query->getSingleScalarResult ();
			$id = "imported-issue-".$project->getId()."-".($count+$inc);
			
			$issue = $this->em->getRepository("DFKIScorecardBundle:Issue")->findOneById($id);
			if( $issue != null ){
				$inc++;
				$id = null;
			}
		}
		
		return $id;
	}
	
	/**
	 * Create a new issue during create project.
	 * This issue will be marked as "imported".
	 *
	 * @param unknown $xml        	
	 * @return \DFKI\ScorecardBundle\Entity\Issue
	 */
	public function createNewIssueFromMeticFile($xml, $project, $parent) {
		$this->em->beginTransaction ();
		$issue = new Issue ();
		
		$issueId = $this->generateIssueId ( $project );
		$issue->setId ( $issueId );
		
		$issue->setParent ( $parent );
		
		$attr = $xml->attributes ();
		
		$label = null;
		if (! empty ( $attr ["label"] )) {
			$label = $attr ["label"];
		} else if (! empty ( $attr ['type'] )) {
			$label = $attr ["type"];
		} else {
			throw new Exception ( "Each issue in the metrics file should contain an attribute \"type\" or \"label\"." );
		}
		
		$issue->setName ( $label );
		$issue->setProject ( $project );
		$issue->setImported ( true );
		
		$this->em->persist ( $issue );
		$this->em->flush ();
		$this->em->commit ();
		return $issue;
	}

	/**
	 * Create a new issue as part of overriding the existing default error typology.
	 * 
	 * @param unknown $typologyFile
	 */
	public function importTypologyFile($typologyFile) {
		libxml_use_internal_errors(true);
		$xml = @simplexml_load_file ( $typologyFile->getPathname () );
		
		if( $xml === false ){
			$msg = "";
			foreach(libxml_get_errors() as $error) {
				$msg .= $error->message;
				$msg .= "<br/>";
			}
				
			throw new Exception("Error parsing metric file: <br/>$msg");
		}

		foreach ( $xml->children() as $errorType ) {
			$this->parseErrorTypeElt($errorType, null);
		}
	}

	/**
	 * Recursive descent parser for typology file
	 * 
	 * @param unknown $elt
	 * @param \DFKI\ScorecardBundle\Entity\Issue $parent
	 * @return \DFKI\ScorecardBundle\Entity\Issue
	 */
	private function parseErrorTypeElt($elt, $parent) {
		$this->em->beginTransaction ();
		$issue = new Issue ();
		
		$attr = $elt->attributes ();
		$name = $attr["name"];
		$id = $attr["id"]; 
		
		$issue->setId( $id );
		$issue->setName( $name );
		$issue->setParent( $parent );
		$issue->setImported ( false );

		$persisted = false;
		foreach ( $elt->children() as $child ) {
			switch ( $child->getName() ) {
				case "description":
					$issue->setDefinition( (string) $child );
					break;
				case "notes":
					$issue->setNotes( (string) $child );
					break;
				case "examples":
					$issue->setExamples( (string) $child );
					break;
				case "errorType":
					$this->em->persist ( $issue );
					$persisted = true;
					$this->parseErrorTypeElt($child, $issue);
					break;
			}
		}
		if (!$persisted) {
			$this->em->persist ($issue );
		}

		$this->em->flush ();
		$this->em->commit ();
	}

	/**
	 * Delete all existing issues.
	 */
	public function deleteIssues() {
		$connection = $this->em->getConnection ();
		$connection->query ( 'SET FOREIGN_KEY_CHECKS=0' );
		$this->em->createQuery ( "DELETE FROM DFKIScorecardBundle:Issue i" )->execute ();
		$connection->query ( 'SET FOREIGN_KEY_CHECKS=1' );
		$this->em->flush ();
		$this->em->commit ();
	}
}
