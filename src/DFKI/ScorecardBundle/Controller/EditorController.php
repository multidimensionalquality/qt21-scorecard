<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
 
namespace DFKI\ScorecardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use DFKI\ScorecardBundle\Entity\Project;
use DFKI\ScorecardBundle\Entity\Segment;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EditorController extends Controller {
	
	/**
	 * display scorecard editor
	 * 
	 * @param unknown $projectId
	 */
	public function editorAction($projectId) {
		
		$project = $this->getDoctrine ()->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		
		if( !is_object($project)){
			throw new BadRequestHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('view', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$projectService = $this->get ( "projectService" );
		$issueDefinitions = $projectService->getProjectIssues($project);
		
		$editorService = $this->get("editorService");
		$issueReports = $editorService->getIssueReports($project);
		
		return $this->render ( 'DFKIScorecardBundle:Editor:editor.html.twig', array (
				"project" => $project,
				"issues" => $issueDefinitions,
				"issueReports" => $issueReports
		) );
	}
	

	/**
	 * mark project as finished and open editor
	 *
	 * @param unknown $projectId
	 */
	public function markAsFinishedAction(Request $req, $projectId){
		$em = $this->getDoctrine()->getEntityManager();
		$project = $em->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		
		if( !is_object($project)){
			throw new BadRequestHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('view', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$project->setFinished( !$project->getFinished() );
		$em->persist($project);
		$em->flush();
		
		$session = $req->getSession();
		$msg = "Your changes have been saved";
		$session->getFlashBag()->add('notice', $msg);
		
		return $this->redirect($this->generateUrl('sc_editor',  array("projectId" => $project->getId())), 301);
	}
//	public function markAsFinishedAction($projectId){
// 		$em = $this->getDoctrine()->getEntityManager();
// 		$project = $em->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		
// 		if( !is_object($project)){
// 			throw new BadRequestHttpException();
// 		}
		
// 		if (false === $this->get('security.context')->isGranted('view', $project)) {
// 			throw new AccessDeniedException('Unauthorised access!');
// 		}
		
// 		$project->setFinished( !$project->getFinished() );
// 		$em->persist($project);
// 		$em->flush();
		
// 		$session = new Session();
// 		$msg = "Your changes have been saved";
// 		$session->getFlashBag()->add('notice', $msg);
		
		//$this->editorAction($projectId);
//	}
}
