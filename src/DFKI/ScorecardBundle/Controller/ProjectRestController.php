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
	public function getUserProjectAction($projectId) {
		$em = $this->getDoctrine ()->getManager ();
		$project = $em->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		if (! is_object ( $project )) {
			return new NotFoundHttpException ();
		}
		return $project->getUsers ();
	}
	
	/**
	 * add user to project
	 *
	 * @Route("/projects/{projectId}/adduser/{userId}", name="rest_add_user_project")
	 */
	public function postAddUserAction($projectId, $userId) {
		$em = $this->getDoctrine ()->getManager ();
		$project = $em->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		if (! is_object ( $project )) {
			return new NotFoundHttpException ( "project not found" );
		}
		$user = $em->getRepository ( "DFKIScorecardBundle:User" )->findOneById ( $userId );
		if (! is_object ( $user )) {
			return new NotFoundHttpException ( "user not found" );
		}
		
		for($i = 0; $i < sizeof ( $project->getUsers () ); $i ++) {
			$users = $project->getUsers ();
			$u = $users [$i];
			if ($u->getId () == $user->getId ()) {
				return;
			}
		}
		
		$project->addUser ( $user );
		$em->persist ( $project );
		$em->flush ();
	}
	
	/**
	 * remove user from project
	 *
	 * @Route("/projects/{projectId}/removeuser/{userId}", name="rest_add_user_project")
	 */
	public function deleteRemoveUserAction($projectId, $userId) {
		$em = $this->getDoctrine ()->getManager ();
		$project = $em->getRepository ( "DFKIScorecardBundle:Project" )->findOneById ( $projectId );
		if (! is_object ( $project )) {
			return new NotFoundHttpException ( "project not found" );
		}
		$user = $em->getRepository ( "DFKIScorecardBundle:User" )->findOneById ( $userId );
		if (! is_object ( $user )) {
			return new NotFoundHttpException ( "user not found" );
		}
		
		$project->removeUser ( $user );
		
		$em->persist ( $project );
		$em->flush ();
	}
}
