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