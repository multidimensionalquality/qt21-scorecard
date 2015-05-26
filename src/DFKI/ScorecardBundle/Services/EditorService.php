<?php

/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Services;

use DFKI\ScorecardBundle\Entity\Project;
use DFKI\ScorecardBundle\DFKIScorecardBundle;
use Doctrine\ORM\EntityManager;

class EditorService {
	protected $em;
	public function __construct(EntityManager $entityManager) {
		$this->em = $entityManager;
	}
	
	/**
	 * get all issue reports assigned to this project
	 *
	 * @param Project $project        	
	 * @return \Doctrine\ORM\array
	 */
	public function getIssueReports(Project $project) {
		$issueReports = $this->em->createQuery ( "SELECT ir FROM DFKIScorecardBundle:IssueReport ir, DFKIScorecardBundle:Segment s 
			WHERE s.project = :project AND ir.segment=s.id" )->setParameter ( "project", $project )->getResult ();
		return $issueReports;
	}
	
	/**
	 * calculate score of project.
	 * returns array with fields "sourceScore", "targetScore", "compositeScore"
	 *
	 * @param unknown $project        	
	 * @throws \Exception
	 * @return multitype:number
	 */
	public function getProjectScore($project) {
		$reports = $this->getIssueReports ( $project );
		
		$targetPenalty = 0;
		$sourcePenalty = 0;
		
		for($i = 0; $i < sizeof ( $reports ); $i ++) {
			$report = $reports [$i];
			
			$severity = 0;
			if ($report->getSeverity () == "minor")
				$severity = 1;
			else if ($report->getSeverity () == "major")
				$severity = 10;
			else if ($report->getSeverity () == "critical")
				$severity = 100;
			else
				throw new \Exception ();
			
			$severity *= $report->getIssueProjectMapping ()->getWeight ();
			
			if ($report->getSide () == "source")
				$sourcePenalty += $severity;
			else
				$targetPenalty += $severity;
		}
		
		$sourceScore = 1 - $sourcePenalty / $project->getSourceWords ();
		$targetScore = 1 - $targetPenalty / $project->getTargetWords ();
		$compositeScore = $targetScore + 1 - $sourceScore;
		
		return array (
				"sourceScore" => $sourceScore * 100,
				"targetScore" => $targetScore * 100,
				"compositeScore" => $compositeScore * 100 
		);
	}
}