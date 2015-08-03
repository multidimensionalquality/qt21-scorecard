<?php
/*
 * Copyright 2015 Deutsches Forschungszentrum für Künstliche Intelligenz
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Services;

use DFKI\ScorecardBundle\Entity\Issue;
use Doctrine\ORM\EntityManager;

class IssueImporter {
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
}