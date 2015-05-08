<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */

namespace DFKI\ScorecardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DFKI\ScorecardBundle\Form\Login;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\Symfony\Component\HttpKernel\Exception;

class AdminController extends Controller {
	
	/**
	 * create a new project
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function postProjectAction(Request $req) {
		
		$securityContext = $this->get("security.context");
		if( !$securityContext->isGranted('ROLE_ADMIN') ){
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$request = Request::createFromGlobals ();
		$metric = $request->files->get ( "metric" );
		$issues = $request->files->get ( "file" );
		
		$projectService = $this->get ( "projectService" );
		
		try{
			$project = $projectService->createProject ();
			$project->addUser($this->getUser());
			$projectService->importMetricFile ( $project, $metric );
			$projectService->importSegmentsFile ( $project, $issues );
			
			$specificationFile = $request->files->get ( "specificationFile" );
			if( !empty($specificationFile)){
				$projectService = $this->get ( "projectService" );
				$projectService->importSpecificationsFile($specificationFile, $project);
			}
		} catch( Exception $e ){
			$session = $req->getSession();
			$msg = sprintf( "Project \"%s\" was created.", $project->getName() );
			$session->getFlashBag()->add('error', $msg);
			return $this->render ( 'DFKIScorecardBundle:Admin:create_project.html.twig', array () );
		}
		
		$this->getDoctrine()->getManager()->flush();
		
		$session = $req->getSession();
		$msg = sprintf( "Project \"%s\" was created.", $project->getName() );
		$session->getFlashBag()->add('notice', $msg);
		
		return $this->render ( 'DFKIScorecardBundle:Admin:create_project.html.twig', array () );
	}
	
	/**
	 * save contents of edit project form
	 * 
	 * @throws NotFoundHttpException
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function postEditProjectAction(Request $req){
		
		$request = Request::createFromGlobals ();
		$pid = $request->get("projectid");
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository("DFKIScorecardBundle:Project")->findOneById($pid);
		
		if( !is_object($project)){
			throw new NotFoundHttpException("Project not found");
		}
		
		if (false === $this->get('security.context')->isGranted('edit', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$project->setName($request->get("project_name"));
		$em->persist($project);
		
		$adduser = $request->get("adduser");
		if( !empty($adduser) ){
			$projectService = $this->get ( "projectService" );
			if( !$projectService->addUser($project,$adduser) ){
				$session = $req->getSession();
				$msg = sprintf( "User \"%s\" could not be found", $adduser );
				$session->getFlashBag()->add('warning', $msg);
			}
		}
		
		$metric = $request->files->get ( "metric" );
		if( !empty($metric)){
			$projectService = $this->get ( "projectService" );

			$em->createQuery("DELETE DFKIScorecardBundle:IssueProjectMapping i WHERE i.project=:project")
			->setParameter("project", $project)
			->execute();
			
			try{
				$projectService->importMetricFile ( $project, $metric );
			} catch( Exception $e ){

				$session = $req->getSession();
				$msg = sprintf( "Project \"%s\" was created.", $project->getName() );
				$session->getFlashBag()->add('error', $msg);
				return $this->redirect($this->generateUrl('sc_edit_project',  array("projectId" => $project->getId())), 301);
			}
		}
		$issues = $request->files->get ( "file" );
		if( !empty($issues)){
			$em->createQuery(
				"DELETE DFKIScorecardBundle:Segment s 
				WHERE s.project=:projectid")
				->setParameter("projectid", $project->getId() )
				->getResult();
			$projectService = $this->get ( "projectService" );
			$projectService->importSegmentsFile ( $project, $issues );
		}
		
		$specificationFile = $request->files->get ( "specificationFile" );
		if( !empty($specificationFile)){
			$projectService = $this->get ( "projectService" );
			$projectService->importSpecificationsFile($specificationFile, $project);
		}

		$session = $req->getSession();
		$msg = sprintf( "You changes to project \"%s\" have been saved.", $project->getName() );
		$session->getFlashBag()->add('notice', $msg);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('sc_edit_project',  array("projectId" => $project->getId())), 301);
	}
	
	/**
	 * load content of edit project form
	 * 
	 * @param unknown $projectId
	 * @throws NotFoundHttpException
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editProjectAction($projectId) {
		$em = $this->getDoctrine ()->getManager ();
		$project = $em->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		if( !is_object($project)){
			throw new NotFoundHttpException("Project not found");
		}
		
		if (false === $this->get('security.context')->isGranted('edit', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
				
		return $this->render ( 'DFKIScorecardBundle:Admin:edit_project.html.twig', array (
				"project" => $project 
		) );
	}
	
	public function deleteProjectAction(Request $req, $projectId){
		
		$em = $this->getDoctrine ()->getManager ();
		$project = $em->getRepository ( 'DFKIScorecardBundle:Project' )->findOneById ( $projectId );
		
		if( !is_object($project)){
			throw new NotFoundHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('edit', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$em->remove($project);
		$em->flush();
		
		$session = $req->getSession();
		$msg = sprintf( "Project \"%s\" was deleted.", $project->getName() );
		$session->getFlashBag()->add('notice', $msg);

		return $this->redirect($this->generateUrl('sc_list_projects'), 301);
	}
	
	/**
	 * remove user from project.
	 * expects get variables userid and projectid
	 */
	public function removeUserFromProjectAction(Request $req){
		
		$request = Request::createFromGlobals ();
		$pid = $request->get("projectid");
		$userid = $request->get("userid");
		
		if( empty($pid) || empty($userid)){
			throw new BadRequestHttpException();
		}
		
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository("DFKIScorecardBundle:Project")->findOneById($pid);
		$user = $em->getRepository("DFKIScorecardBundle:User")->findOneById($userid);
		
		if( !is_object($project) || !is_object($user)){
			throw new NotFoundHttpException();
		}
		
		if (false === $this->get('security.context')->isGranted('edit', $project)) {
			throw new AccessDeniedException('Unauthorised access!');
		}
		
		$project->removeUser($user);
		$em->persist($project);
		$em->flush();
	
		$session = $req->getSession();
		$msg = sprintf( "User \"%s\" was removed from project \"%s\".", $user->getUsername(), $project->getName() );
		$session->getFlashBag()->add('notice', $msg);
		
		return $this->redirect($this->generateUrl('sc_edit_project',  array("projectId" => $project->getId())), 301);
	}
}
