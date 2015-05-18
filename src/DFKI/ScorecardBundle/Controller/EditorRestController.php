<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */

namespace DFKI\ScorecardBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use DFKI\ScorecardBundle\Entity\Segment;
use DFKI\ScorecardBundle\Entity\Issue;
use DFKI\ScorecardBundle\Entity\IssueReport;
use DFKI\ScorecardBundle\Entity\IssueProjectMapping;
use Symfony\Component\HttpKernel\Exception\Symfony\Component\HttpKernel\Exception;

class EditorRestController extends Controller {
	
	/**
	 * add issue report
	 * request variables: segmentid, issueid, severity, side, tempReportId
	 * tempReportId will be just transfered back so the gui can update that id
	 * 
	 * @Route("/editor/issues", name="rest_editor_add_issue")
	 */
	public function postIssueAction(){

		$request = Request::createFromGlobals ();
		
		$segmentid = $request->get("segmentid");
		$issueid = $request->get("issueid");
		$severity = $request->get("severity");
		$side = $request->get("side");
		$tempReportId = $request->get("tempReportId");
		
		if( empty( $segmentid ) ||
			empty( $issueid ) || 
			empty( $severity ) ||
			empty( $side ) ||
			empty( $tempReportId ) ||
			!($severity == "minor" || $severity == "critical" || $severity == "major") ||
			!($side == "source" || $side ="target")){
			throw new BadRequestHttpException();
		}
		
		$em = $this->getDoctrine()->getManager();
		$segment = $em->getRepository("DFKIScorecardBundle:Segment")->findOneById($segmentid);
		$issue = $em->getRepository("DFKIScorecardBundle:Issue")->findOneById($issueid);
		
		if( !is_object($segment) || !is_object($issue)){
			throw new NotFoundHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('view', $segment->getProject())) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$issueProjectMapping = $em->getRepository("DFKIScorecardBundle:IssueProjectMapping")->findOneBy(array("project" => $segment->getProject(), "issue" => $issue ));
		if( !is_object($issueProjectMapping )){
			throw new HttpException(500);
		}
		
		$report = new IssueReport();
		$report->setSide($side);
		$report->setSeverity($severity);
		$report->setSegment($segment);
		$report->setIssue($issue);
		$report->setIssueProjectMapping($issueProjectMapping);
		
		$em->persist($report);
		$em->flush();
		
		$data = $this->serializeReport($report);
		$data["tempReportId"] = $tempReportId;
		return $data;
	}
	
	/**
	 * helper function. serialize import fields from issue report
	 * 
	 * @param unknown $report
	 * @return multitype:NULL
	 */
	private function serializeReport($report){
		return array(
			"id" => $report->getId(),
			"issueName" => $report->getIssue()->getName(),
			"issueId" => $report->getIssue()->getId(),
			"side" => $report->getSide(),
			"severity" => $report->getSeverity()
		);
	}
	
	/**
	 * delete issue report
	 * 
	 * @Route("/editor/issues/{issueReportId}", name="rest_editor_delete_issue")
	 * @param unknown $issueId
	 */
	public function deleteIssuesAction($issueReportId){
		if( $issueReportId == null ){
			throw new BadRequestHttpException();
		}
		$em = $this->getDoctrine()->getManager();
		$issueReport = $em->getRepository("DFKIScorecardBundle:IssueReport")->findOneById($issueReportId);
		
		if( !is_object($issueReport)){
			throw new NotFoundHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('view', $issueReport->getProject())) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$em->remove($issueReport);
		$em->flush();
		
		return $this->serializeReport($issueReport);
	}

	/**
	 * save notes for a given segment.
	 * expects post variables segment, notes
	 * 
	 * @Route("/editor/notes", name="rest_editor_post_notes")
	 */
	public function postNotesAction(){
		$request = Request::createFromGlobals ();
		$segmentid = $request->get("segment");
		$notes = $request->get("notes");
		
		if( empty($segmentid) ||
			empty($notes)){
			return new BadRequestHttpException();
		}
		
		$em = $this->getDoctrine()->getManager();
		$segment = $em->getRepository("DFKIScorecardBundle:Segment")->findOneById($segmentid);
		
		if( !is_object($segment)){
			throw new NotFoundHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('view', $segment->getProject())) {
			throw new AccessDeniedException('Unauthorised access!');
		}		
		
		$segment->setNotes($notes);
		$em->persist($segment);
		$em->flush();
	}

	/**
	 * delete issue report. expects post variable "highlights" and "side"
	 *
	 * @Route("/editor/segments/{segmentid}", name="rest_editor_set_highlight")
	 * @param unknown $issueId
	 */
	public function postHighlightsAction( $segmentid ){
		$request = Request::createFromGlobals ();
		$highlights = $request->get("highlights");
		$side = $request->get("side");
		
		if( $highlights == null ||
			!($side == "source" || $side == "target" )){
			throw new BadRequestHttpException();
		}
		
		$em = $this->getDoctrine()->getManager();
		$segment = $em->getRepository("DFKIScorecardBundle:Segment")->findOneById($segmentid);
		
		if( !is_object($segment)){
			throw new NotFoundHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('view', $segment->getProject())) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		if( $side == "source" ){
			$segment->setHighlightsSource($highlights);
		} else if( $side == "target" ){
			$segment->setHighlightsTarget($highlights);
		}
		
		$em->persist($segment);
		$em->flush();
	}

	/**
	 * get project score
	 *
	 * @Route("/editor/projectscore/{projectid}", name="rest_editor_get_project_score")
	 */
	public function getProjectScoreAction($projectid){

		$em = $this->getDoctrine()->getManager();
		$project= $em->getRepository("DFKIScorecardBundle:Project")->findOneById($projectid);

		if( !is_object($project)){
			throw new NotFoundHttpException();
		}

		if (false === $this->get('security.context')->isGranted('view', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$editorService = $this->get("editorService");
		return $editorService->getProjectScore($project);
	}
	
	/**
	 * set last touched segment
	 *
	 * @Route("/editor/segment/touch/{segmentid}", name="rest_editor_touch_segment")
	 */
	public function postTouchSegmentAction($segmentid){
		$em = $this->getDoctrine()->getManager();
		$segment = $em->getRepository("DFKIScorecardBundle:Segment")->findOneById($segmentid);
		
		if( !is_object($segment)){
			throw new NotFoundHttpException();
		}
		
		$project = $segment->getProject();
		if (false === $this->get('security.context')->isGranted('view', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$projectService = $this->get("projectService");
		$completion = $projectService->getProjectCompletion($project, $segment);
		
		$project->setLastTouchedSegment($segment);
		$project->setCompletion($completion);

		$em->persist($project);
		$em->flush();
	}
}