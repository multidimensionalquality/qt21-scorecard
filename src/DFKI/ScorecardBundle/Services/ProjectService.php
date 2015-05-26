<?php

/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Services;

use DFKI\ScorecardBundle\Entity\Project;
use DFKI\ScorecardBundle\Entity\Issue;
use DFKI\ScorecardBundle\Entity\IssueProjectMapping;
use DFKI\ScorecardBundle\Entity\Segment;
use DFKI\ScorecardBundle\Entity\SegmentMetadata;
use DFKI\ScorecardBundle\Entity\SegmentMetadataCategory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DFKI\ScorecardBundle\Entity\IssueReport;
use Exception;
use DFKI\ScorecardBundle\DFKIScorecardBundle;

class ProjectService {
	protected $em;
	protected $securityContext;
	protected $htmlPurifier;
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, $htmlPurifier) {
		$this->securityContext = $securityContext;
		$this->em = $entityManager;
		$this->htmlPurifier = $htmlPurifier;
	}
	
	/**
	 * create project entity
	 */
	public function createProject() {
		$request = Request::createFromGlobals ();
		
		$project = new Project ();
		$project->setName ( $request->get ( "project_name" ) );
		$project->setSourceWords ( 0 );
		$project->setTargetWords ( 0 );
		
		$user = $this->securityContext->getToken ()->getUser ();
		$project->setCreatedBy ( $user );
		
		$date = new \DateTime ();
		$project->setCreatedOn ( $date );
		$project->setUpdatedOn ( $date );
		
		$project->setMetric ( "" );
		$project->setFilename ( "" );
		$project->setMetricName ( "" );
		
		$this->em->persist ( $project );
		
		return $project;
	}
	
	/**
	 * import metric file
	 * deletes all old issues mappings of this project
	 *
	 * @param unknown $project        	
	 * @param unknown $metric        	
	 */
	public function importMetricFile($project, $metric) {
		$str = file_get_contents ( $metric->getPathname () );
		$project->setMetric ( $str );
		$project->setMetricName ( $metric->getClientOriginalName () );
		$this->em->persist ( $project );
		
		$xml = simplexml_load_file ( $metric->getPathname () );
		foreach ( $xml->issue as $issue ) {
			try {
				$this->traverseMetric ( $issue, $project );
			} catch ( Exception $e ) {
				throw $e;
			}
		}
	}
	
	/**
	 * recursivly iterate through metric file
	 *
	 * @param unknown $xml        	
	 * @param unknown $parent        	
	 * @param unknown $project        	
	 * @throws Exception
	 * @return \DFKI\ScorecardBundle\Entity\Metric
	 */
	private function traverseMetric($xml, $project) {
		$attr = $xml->attributes ();
		
		$id = ( string ) $attr ["type"];
		$issue = $this->em->getRepository ( "DFKIScorecardBundle:Issue" )->findOneById ( $id );
		
		if (! is_object ( $issue )) {
			throw new Exception ( sprintf ( "Could not find issue \"%s\" which is referenced in metric file.", $id ) );
		} else {
			
			$ipo = new IssueProjectMapping ();
			$ipo->setIssue ( $issue );
			$display = null;
			if (strtolower ( $attr ["display"] ) == "yes") {
				$display = true;
			} else if (strtolower ( $attr ["display"] ) == "no") {
				$display = false;
			} else {
				throw new Exception ( "Bad metric xml input file. Could not parse display attribute \"" . $attr ["display"] . "\"" );
			}
			$ipo->setDisplay ( $display );
			$ipo->setProject ( $project );
			$this->em->persist ( $ipo );
			
			foreach ( $xml->children () as $child ) {
				$this->traverseMetric ( $child, $project );
			}
		}
	}
	
	/**
	 * import segments file
	 *
	 * @param unknown $project        	
	 * @param unknown $file        	
	 */
	public function importSegmentsFile($project, $file) {
		$filecontent = file_get_contents ( $file->getPathname () );
		if (strlen ( trim ( $filecontent ) ) == 0) {
			throw new \Exception ( "invalid bitext file" );
		}
		$filecontent = explode ( "\n", $filecontent );
		
		$project->setFileName ( $file->getClientOriginalName () );
		
		$nColumns = sizeof ( explode ( "\t", $filecontent [0] ) );
		if ($nColumns == 2) {
			$this->importTwoColumnBitextFile ( $filecontent, $project );
		} else {
			$this->importMulticolumnBitextFile ( $filecontent, $project );
		}
	}
	
	/**
	 * Import a bitext file with additional metadata
	 *
	 * @param unknown $filecontent        	
	 * @param unknown $project        	
	 */
	private function importMulticolumnBitextFile($filecontent, $project) {
		$header = explode ( "\t", $filecontent [0] );
		$nColumns = sizeof ( $header );
		
		// create metadata categories from header row
		$categories = array ();
		for($i = 2; $i < $nColumns; $i ++) {
			$meta = new SegmentMetadataCategory ();
			$meta->setName ( $header [$i] );
			$this->em->persist ( $meta );
			$categories [$i - 2] = $meta;
		}
		$this->em->flush ();
		
		// import bitext file
		$seg_number = 0;
		foreach ( $filecontent as $line ) {
			
			if ($seg_number == 0) {
				// skip first line
				$seg_number ++;
				continue;
			}
			
			if (strlen ( trim ( $line ) ) == 0) {
				// skip empty lines
				continue;
			}
			
			$line = explode ( "\t", $line );
			
			$seg_number ++;
			if (sizeof ( $line ) != $nColumns) {
				throw new \Exception ( "invalid bitext file in line " . $seg_number );
			}
			
			$segment = $this->createSegment ( $line, $seg_number, $project );
			$project->addSegment ( $segment );
			$this->em->persist ( $segment );
			
			for($i = 2; $i < sizeof ( $line ); $i ++) {
				$meta = new SegmentMetadata ();
				$meta->setCategory ( $categories [$i - 2] );
				$meta->setText ( $line [$i] );
				$meta->setSegment ( $segment );
				$segment->addMetadatum ( $meta );
				$this->em->persist ( $meta );
			}
			
			if ($seg_number % 500 == 0) {
				$this->em->flush ();
			}
		}
		
		$this->updateCounts ( $project );
		$this->em->persist ( $project );
		
		$this->em->flush ();
	}
	
	/**
	 * Import a bitext file without header row and two columns
	 *
	 * @param unknown $filecontent        	
	 * @param unknown $project        	
	 * @throws Exception
	 */
	private function importTwoColumnBitextFile($filecontent, $project) {
		$seg_number = 1;
		
		foreach ( $filecontent as $thisLine ) {
			
			if (strlen ( trim ( $thisLine [0] ) ) == 0) {
				continue;
			}
			
			$thisLine = explode ( "\t", $thisLine );
			
			if (sizeof ( $thisLine ) != 2) {
				throw new Exception ( "Error reading bitext file in line " . $seg_number );
			}
			
			$segment = $this->createSegment ( $thisLine, $seg_number, $project );
			$this->em->persist ( $segment );
			$project->addSegment ( $segment );
			
			$seg_number ++;
			
			if ($seg_number % 500 == 0) {
				$this->em->flush ();
			}
		}
		
		$this->updateCounts ( $project );
		$this->em->persist ( $project );
		
		$this->em->flush ();
	}
	
	/**
	 * helper function that strips html from bitext files
	 *
	 * @param unknown $text        	
	 */
	private function cleanHtml($text) {
		$clean = preg_replace ( "@[  ]+@", " ", $text );
		$clean = $this->htmlPurifier->purify ( $clean );
		return $clean;
	}
	
	/**
	 * helper function for importing bitext files.
	 * creates a single segment
	 *
	 * @param unknown $line
	 *        	an array representing a bitext file line column wise
	 * @param unknown $seg_number        	
	 * @param unknown $project        	
	 * @return \DFKI\ScorecardBundle\Entity\Segment
	 */
	private function createSegment($line, $seg_number, $project) {
		// TODO one is non breaking space
		$sourceSeg = $this->cleanHtml ( $line [0] );
		$targetSeg = $this->cleanHtml ( $line [1] );
		
		$segment = new Segment ();
		$segment->setProject ( $project );
		$segment->setSegNum ( $seg_number );
		$segment->setSource ( $sourceSeg );
		$segment->setTarget ( $targetSeg );
		$segment->setComments ( "" );
		return $segment;
	}
	
	/**
	 * calculate source and target count for project
	 *
	 * @param unknown $project        	
	 */
	private function updateCounts($project) {
		$sourceCount = 0;
		$targetCount = 0;
		for($i = 0; $i < sizeof ( $project->getSegments () ); $i ++) {
			$segment = $project->getSegments ()[$i];
			$sourceCount = $sourceCount + count ( explode ( " ", $segment->getSource () ) );
			$targetCount = $targetCount + count ( explode ( " ", $segment->getTarget () ) );
		}
		$project->setSourceWords ( $sourceCount );
		$project->setTargetWords ( $targetCount );
	}
	
	/**
	 * Find user $search and add to $project.
	 * Find user by
	 * 1. username
	 * 2. name
	 * 3. id
	 * 4. email
	 *
	 * @param unknown $project        	
	 * @param string $search        	
	 * @return boolean true if user was found, else false
	 */
	public function addUser($project, $search) {
		$user = $this->em->getRepository ( "DFKIScorecardBundle:User" )->findOneByUsername ( $search );
		if (! is_object ( $user )) {
			$user = $this->em->getRepository ( "DFKIScorecardBundle:User" )->findOneByName ( $search );
		}
		if (! is_object ( $user )) {
			$user = $this->em->getRepository ( "DFKIScorecardBundle:User" )->findOneById ( $search );
		}
		if (! is_object ( $user )) {
			$user = $this->em->getRepository ( "DFKIScorecardBundle:User" )->findOneByEmail ( $search );
		}
		
		if (! is_object ( $user )) {
			return false;
		}
		
		if (in_array ( $user, $project->getUsers ()->toArray () )) {
			return true;
		}
		
		$project->addUser ( $user );
		$this->em->persist ( $project );
		$this->em->flush ();
		return true;
	}
	
	/**
	 * return a mapping from every issue id to its top level issue in the issue tree
	 */
	private function getIssueToTopMapping() {
		$issues = $this->em->getRepository ( "DFKIScorecardBundle:Issue" )->findAll ();
		$map = array ();
		$parents = array ();
		for($i = 0; $i < sizeof ( $issues ); $i ++) {
			$issue = & $issues [$i];
			if ($issue->getParent () == null) {
				$parents [] = $issue;
			}
		}
		foreach ( $parents as $parent ) {
			$map [$parent->getId ()] = $parent->getId ();
			$children = $this->getAllIssueChildren ( $parent->getId (), $issues );
			foreach ( $children as $child ) {
				$map [$child->getId ()] = $parent->getId ();
			}
		}
		return $map;
	}
	
	/**
	 * return all issues of one project
	 *
	 * @param unknown $project        	
	 * @return \Doctrine\ORM\array
	 */
	public function getProjectIssues($project) {
		$issues = $this->em->createQuery ( "SELECT i.id, i.name, m.display, i.notes, i.definition, i.examples FROM DFKIScorecardBundle:Issue i, DFKIScorecardBundle:IssueProjectMapping m, DFKIScorecardBundle:Project p 
				WHERE p.id = :project
				AND m.project = p
				AND m.issue=i" )->setParameter ( "project", $project )->getResult ();
		
		$topMapping = $this->getIssueToTopMapping ();
		
		$map = array ();
		foreach ( $issues as $issue ) {
			$parent = $topMapping [$issue ["id"]];
			if (! isset ( $map [$parent] ))
				$map [$parent] = array ();
			$map [$parent] [] = $issue;
		}
		
		$list = array ();
		foreach ( array_keys ( $map ) as $key ) {
			$list [] = $map [$key];
		}
		
		// generate tooltips
		for($i = 0; $i < sizeof ( $list ); $i ++) {
			
			for($j = 0; $j < sizeof ( $list [$i] ); $j ++) {
				$issue = $list [$i] [$j];
				$html = "<h2>" . $issue ["name"] . "</h2>";
				$html .= "<ul><li><strong>MQM id</strong>: " . $issue ["id"] . "</li>";
				$html .= "<li><strong>Description</strong>: " . $issue ["definition"] . "</li>";
				$parent = $j == 0 ? $issue ["name"] . " is a top-level MQM category." : $issue ["name"] . " is a type of " . $list [$i] [0] ["name"];
				$html .= "<li><strong>Parent:</strong> " . $parent . "</li>";
				$html .= "<li><strong>Applies to</strong>: ";
				if ($issue ["id"] == "accuracy")
					$html .= "target";
				else if ($issue ["id"] == "internationalization")
					$html .= "source";
				else
					$html .= "source and target";
				$html .= "</li></ul>";
				$examples = ! empty ( $issue ["examples"] ) ? $issue ["examples"] : "<i>none</i>";
				$html .= "<h3>Examples</h3><ul>" . $examples . "</ul>";
				$notes = ! empty ( $issue ["notes"] ) ? $issue ["notes"] : "<i>none</i>";
				$html .= "<h3>Notes</h3><ul>" . $notes . "</ul>";
				$list [$i] [$j] ["tooltip"] = $html;
			}
		}
		
		return $list;
	}
	
	/**
	 * return a list of all issues that are subordinated to given issueid in the issue tree
	 *
	 * @param unknown $parentid        	
	 * @param unknown $issues        	
	 * @return Ambigous <array, multitype:unknown >
	 */
	private function getAllIssueChildren($parentid, $issues) {
		$children = array ();
		for($i = 0; $i < sizeof ( $issues ); $i ++) {
			$issue = & $issues [$i];
			
			if (($parentid == null && $issue->getParent () == null) || ($issue->getParent () != null && $issue->getParent ()->getId () == $parentid)) {
				$children [] = $issue;
				$childchilds = $this->getAllIssueChildren ( $issue->getId (), $issues );
				$children = array_merge ( $children, $childchilds );
			}
		}
		return $children;
	}
	
	/**
	 * Convert specifications xml to html and save that in the project.
	 *
	 * @param unknown $file        	
	 * @param unknown $project        	
	 */
	public function importSpecificationsFile($file, $project) {
		try {
			$xml = new \SimpleXMLElement ( file_get_contents ( $file->getPathname () ) );
		} catch ( \Exception $e ) {
			throw new \Exception ( "Specifications file is not valid xml" );
		}
		$html = "";
		
		if (empty ( $xml->section )) {
			throw new Exception ( "Invalid specification file" );
		}
		
		foreach ( $xml->section as $section ) {
			$html .= "<h3>" . $section ["name"] . "</h3>";
			$html .= "\r\n<div>\r\n";
			
			foreach ( $section->parameter as $parameter ) {
				$html .= "<h4>[" . $parameter ["number"] . "] " . $parameter ["name"] . "</h4>\r\n";
				
				foreach ( $parameter->subparameter as $subparameter ) {
					$html .= "<h5>[" . $subparameter ["number"] . "] " . $subparameter ["name"] . "</h5>\r\n";
					
					if (sizeof ( $subparameter->value ) > 0) {
						$html .= "<ul>\r\n";
						foreach ( $subparameter->value as $value ) {
							$html .= "<li>" . $value . "</li>\r\n";
						}
						$html .= "</ul>\r\n";
					}
				}
			}
			$html .= "</div>\r\n";
		}
		
		$project->setSpecifications ( $html );
		$project->setSpecificationsFileName ( $file->getClientOriginalName () );
		$this->em->persist ( $project );
		$this->em->flush ();
	}
	
	/**
	 * Calculate completion of project as (lastTouchedSegment-1)/(highestSegment-1)
	 *
	 * @param unknown $project        	
	 * @param unknown $lastTouchedSegment        	
	 * @return number
	 */
	public function getProjectCompletion($project, $lastTouchedSegment) {
		$maxSegmentRaw = $this->em->createQuery ( "SELECT MAX(s.segNum) FROM DFKIScorecardBundle:Segment s, DFKIScorecardBundle:Project p
			WHERE s.project=:project" )->setParameter ( "project", $project )->getResult ();
		
		$maxSegment = intval ( $maxSegmentRaw [0] [1] );
		
		if ($lastTouchedSegment == null) {
			return 0.0;
		} else if ($maxSegment == 1) {
			if ($lastTouchedSegment->getSegNum () == $maxSegment) {
				return 1.0;
			} else {
				return 0.0;
			}
		} else {
			return ( float ) ($lastTouchedSegment->getSegNum () - 1) / ($maxSegment - 1);
		}
	}
}