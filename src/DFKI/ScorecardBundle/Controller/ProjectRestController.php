<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */

namespace DFKI\ScorecardBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectRestController extends Controller {
	
	/**
	 * get users that are part of a project
	 * 
	 * @Route("/projects/{projectId}/users", name="rest_users_project")
	 */
	public function getUserProjectAction($projectId){
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository("DFKIScorecardBundle:Project")->findOneById($projectId);
		if( !is_object($project)){
			return new NotFoundHttpException();
		}
		return $project->getUsers();
	}

	/**
	 * add user to project
	 *
	 * @Route("/projects/{projectId}/adduser/{userId}", name="rest_add_user_project")
	 */
	public function postAddUserAction($projectId, $userId){
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository("DFKIScorecardBundle:Project")->findOneById($projectId);
		if( !is_object($project)){
			return new NotFoundHttpException( "project not found" );
		}
		$user = $em->getRepository("DFKIScorecardBundle:User")->findOneById($userId);
		if( !is_object($user)){
			return new NotFoundHttpException( "user not found" );
		}
		
		for( $i=0; $i<sizeof($project->getUsers()); $i++ ){
			$users = $project->getUsers();
			$u = $users[$i];
			if( $u->getId() == $user->getId() ){
				return;
			}
		}
		
		$project->addUser($user);		
		$em->persist($project);
		$em->flush();
	}
	
	/**
	 * remove user from project
	 *
	 * @Route("/projects/{projectId}/removeuser/{userId}", name="rest_add_user_project")
	 */
	public function deleteRemoveUserAction($projectId, $userId ){
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository("DFKIScorecardBundle:Project")->findOneById($projectId);
		if( !is_object($project)){
			return new NotFoundHttpException( "project not found" );
		}
		$user = $em->getRepository("DFKIScorecardBundle:User")->findOneById($userId);
		if( !is_object($user)){
			return new NotFoundHttpException( "user not found" );
		}
		
		$project->removeUser($user);
		$em->persist($project);
		$em->flush();
	}
}
