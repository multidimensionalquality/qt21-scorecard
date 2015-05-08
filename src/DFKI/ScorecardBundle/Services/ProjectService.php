<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */

namespace DFKI\ScorecardBundle\Services;

use DFKI\ScorecardBundle\Entity\Project;
use DFKI\ScorecardBundle\Entity\Issue;
use DFKI\ScorecardBundle\Entity\IssueProjectMapping;
use DFKI\ScorecardBundle\Entity\Segment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DFKI\ScorecardBundle\Entity\IssueReport;
use Exception;

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
		$project->setName ( $request->get("project_name") );
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
		$project->setMetricName($metric->getClientOriginalName() );
		$this->em->persist ( $project );
		
		$xml = simplexml_load_file ($metric->getPathname ());
		foreach( $xml->issue as $issue ){
			try{
				$this->traverseMetric($issue, $project);
			} catch( Exception $e ){
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
	private function traverseMetric($xml, $project){
		
		$attr = $xml->attributes();
		
		$id = (string)$attr["type"];
		$issue = $this->em->getRepository("DFKIScorecardBundle:Issue")->findOneById($id);
		
		if( !is_object($issue)){
			throw new Exception(sprintf("Could not find issue \"%s\"", $id));
		} else{
			
			$ipo = new IssueProjectMapping();
			$ipo->setIssue($issue);
			$display = null;
			if( strtolower($attr["display"] ) == "yes" ){
				$display = true;
			} else if( strtolower($attr["display"]) == "no" ){
				$display = false;
			} else{
				throw new Exception( "Bad xml input file. Could not parse display attribute \"".$attr["display"]."\"" );
			}
			$ipo->setDisplay($display);
			$ipo->setProject($project);
			$this->em->persist($ipo);
			
			foreach($xml->children() as $child){
				$this->traverseMetric($child, $project);
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
		$filecontent = explode ( "\n", $filecontent );
		$seg_number = 1;
		$sourceCount = 0;
		$targetCount = 0;
		
		foreach ( $filecontent as $thisLine ) {
			$thisLine = explode ( "\t", $thisLine );
			
			//TODO one is non breaking space
			$sourceSeg = preg_replace ( "@[  ]+@", " ", $thisLine [0] );
			$sourceCount = $sourceCount + count ( explode ( " ", $sourceSeg ) );
			$sourceSeg = $this->htmlPurifier->purify ( $sourceSeg );
			
			$targetSeg = preg_replace ( "@[  ]+@", " ", $thisLine [1] );
			$targetCount = $targetCount + count ( explode ( " ", $targetSeg ) );
			$targetSeg = $this->htmlPurifier->purify ( $targetSeg );
			
			$segment = new Segment ();
			$segment->setProject ( $project );
			$segment->setSegNum ( $seg_number );
			$segment->setSource ( $sourceSeg );
			$segment->setTarget ( $targetSeg );
			$segment->setComments ( "" );
			$this->em->persist ( $segment );
			
			$seg_number ++;
			
			if ($seg_number % 500 == 0) {
				$this->em->flush ();
			}
		}

		$project->setFileName($file->getClientOriginalName() );
		$project->setSourceWords ( $sourceCount );
		$project->setTargetWords ( $targetCount );
		$this->em->persist($project);
		
		$this->em->flush ();
	}
	
	/**
	 * Find user $search and add to $project. Find user by
	 * 1. username
	 * 2. name
	 * 3. id
	 * 4. email
	 * 
	 * @param unknown $project
	 * @param string $search
	 * @return boolean true if user was found, else false
	 */
	public function addUser($project,$search){
		
		$user = $this->em->getRepository("DFKIScorecardBundle:User")->findOneByUsername($search);
		if( !is_object($user)){
			$user = $this->em->getRepository("DFKIScorecardBundle:User")->findOneByName($search);
		}
		if( !is_object($user)){
			$user = $this->em->getRepository("DFKIScorecardBundle:User")->findOneById($search);
		}
		if( !is_object($user)){
			$user = $this->em->getRepository("DFKIScorecardBundle:User")->findOneByEmail($search);
		}
		
		if( !is_object($user)){
			return false;
		}
		
		if( in_array($user, $project->getUsers()->toArray())){
			return true;
		}
		
		$project->addUser($user);
		$this->em->persist($project);
		$this->em->flush();
		return true;			
	}
	
	/**
	 * return a mapping from every issue id to its top level issue in the issue tree
	 */
	private function getIssueToTopMapping(){
		$issues = $this->em->getRepository("DFKIScorecardBundle:Issue")->findAll();
		$map = array();
		$parents = array();
		for( $i=0; $i<sizeof($issues); $i++ ){
			$issue = & $issues[$i];
			if( $issue->getParent() == null ){
				$parents[] = $issue;
			}
		}
		foreach($parents as $parent){
			$map[$parent->getId()] = $parent->getId();
			$children = $this->getAllIssueChildren($parent->getId(), $issues);
			foreach($children as $child){
				$map[$child->getId()]=$parent->getId();
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
	public function getProjectIssues($project){
		$issues = $this->em->createQuery(
			"SELECT i.id, i.name, m.display, i.notes, i.definition, i.examples FROM DFKIScorecardBundle:Issue i, DFKIScorecardBundle:IssueProjectMapping m, DFKIScorecardBundle:Project p 
				WHERE p.id = :project
				AND m.project = p
				AND m.issue=i")
			->setParameter("project", $project)
			->getResult();
		
		$topMapping = $this->getIssueToTopMapping();
		
		$map = array();
		foreach($issues as $issue){
			$parent = $topMapping[$issue["id"]];
			if(!isset($map[$parent]))
				$map[$parent] = array();
			$map[$parent][] = $issue;
		}
		
		
		$list = array();
		foreach( array_keys($map) as $key ){
			$list[] = $map[$key];
		}
		
		// generate tooltips
		for( $i=0; $i<sizeof( $list ); $i++ ){
			
			for( $j=0; $j<sizeof( $list[$i] ); $j++ ){
				$issue = $list[$i][$j];
				$html = "<h2>".$issue["name"]."</h2>";
				$html .= "<ul><li><strong>MQM id</strong>: ".$issue["id"]."</li>";
				$html .= "<li><strong>Description</strong>: ".$issue["definition"]."</li>";
				$parent = $j==0 ? $issue["name"]." is a top-level MQM category." : $issue["name"]." is a type of ".$list[$i][0]["name"];
				$html .= "<li><strong>Parent:</strong> ".$parent."</li>";
				$html .= "<li><strong>Applies to</strong>: ";
				if( $issue["id"] == "accuracy" ) $html .= "target";
				else if( $issue["id"] == "internationalization" ) $html .= "source";
				else $html .= "source and target";
				$html .= "</li></ul>";
				$examples = !empty( $issue["examples"] ) ? $issue["examples"] : "<i>none</i>";
				$html .= "<h3>Examples</h3><ul>".$examples."</ul>";
				$notes = !empty( $issue["notes"] ) ? $issue["notes"] : "<i>none</i>";
				$html .= "<h3>Notes</h3><ul>".$notes."</ul>";
				$list[$i][$j]["tooltip"] = $html;
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
	private function getAllIssueChildren($parentid,$issues){
		$children = array();
		for($i=0; $i<sizeof($issues); $i++){
			$issue = & $issues[$i];
			
			if( ($parentid==null && $issue->getParent() == null) ||
				($issue->getParent() != null && $issue->getParent()->getId() == $parentid )){
				$children[] = $issue;
				$childchilds = $this->getAllIssueChildren($issue->getId(), $issues);
				$children = array_merge($children,$childchilds);
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
	public function importSpecificationsFile($file, $project){
		$xml = new \SimpleXMLElement( file_get_contents ( $file->getPathname () ));
		$html = "";
		
		if( empty($xml->section )){
			throw new Exception("Invalid specification file");
		}
		
		foreach($xml->section as $section){
			$html .= "<h3>".$section["name"]."</h3>";
			$html .= "\r\n<div>\r\n";
			
			foreach($section->parameter as $parameter){
				$html .= "<h4>[".$parameter["number"]."] ".$parameter["name"]."</h4>\r\n";
				
				foreach($parameter->subparameter as $subparameter){
					$html .= "<h5>[".$subparameter["number"]."] ".$subparameter["name"]."</h5>\r\n";
					
					if( sizeof( $subparameter->value ) > 0 ){
						$html .= "<ul>\r\n";
						foreach($subparameter->value as $value){
							$html .= "<li>".$value."</li>\r\n";
						}
						$html .= "</ul>\r\n";
					}
				}
			}
			$html .= "</div>\r\n";
		}
		
		$project->setSpecifications( $html );
 		$project->setSpecificationsFileName($file->getClientOriginalName() );
 		$this->em->persist($project);
 		$this->em->flush();
	}
}