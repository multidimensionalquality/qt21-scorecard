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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DFKI\ScorecardBundle\Form\Login;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DFKI\ScorecardBundle\Entity\User;

class SuperadminController extends Controller {
	
	/**
	 * load pagination table with users and send this to the view
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listUsersAction() {
		if (! $this->get ( "security.context" )->isGranted ( 'ROLE_SUPER_ADMIN' )) {
			throw new AccessDeniedException ( 'Unauthorised access!' );
		}
		
		$em = $this->get ( 'doctrine.orm.entity_manager' );
		$dql = "SELECT u FROM DFKIScorecardBundle:User u";
		$query = $em->createQuery ( $dql );
		
		$request = Request::createFromGlobals ();
		$paginator = $this->get ( 'knp_paginator' );
		$pagination = $paginator->paginate ( $query, $request->query->get ( 'page', 1 )/*page number*/,
				10/*limit per page*/
		);
		
		return $this->render ( 'DFKIScorecardBundle:Superadmin:manage_users.html.twig', array (
				"pagination" => $pagination 
		) );
	}
	
	/**
	 * Set the role of a user and redirect to user list
	 */
	public function setUserRoleAction(Request $req) {
		if (! $this->get ( "security.context" )->isGranted ( 'ROLE_SUPER_ADMIN' )) {
			throw new AccessDeniedException ( 'Unauthorised access!' );
		}
		
		// input validation
		$request = Request::createFromGlobals ();
		$userid = $request->get ( "userid" );
		$role = $request->get ( "role" );
		
		$validRoles = array (
				"ROLE_USER",
				"ROLE_ADMIN",
				"ROLE_SUPER_ADMIN" 
		);
		
		if (empty ( $userid ) || empty ( $role ) || ! is_numeric ( $userid ) || ! in_array ( $role, $validRoles )) {
			throw new BadRequestHttpException ();
		}
		
		// set new role
		$em = $this->getDoctrine ()->getEntityManager ();
		$user = $em->getRepository ( 'DFKIScorecardBundle:User' )->findOneById ( $userid );
		
		if (! is_object ( $user )) {
			throw new BadRequestHttpException ();
		}
		
		$user->setRoles ( array (
				$role 
		) );
		$em->persist ( $user );
		$em->flush ();
		
		$session = $req->getSession ();
		$msg = sprintf ( "You changed the role of user \"%s\" to \"%s\"", $user->getUsername (), $role );
		$session->getFlashBag ()->add ( 'notice', $msg );
		
		$em->flush ();
		
		return $this->redirect ( $this->generateUrl ( 'sc_manage_users' ), 301 );
	}
	
	/**
	 * delete user and redirect to list users view
	 *
	 * @param unknown $userid        	
	 * @throws AccessDeniedException
	 */
	function deleteUserAction(Request $req, $userid) {
		if (! $this->get ( "security.context" )->isGranted ( 'ROLE_SUPER_ADMIN' )) {
			throw new AccessDeniedException ( 'Unauthorised access!' );
		}
		if (empty ( $userid ) || ! is_numeric ( $userid )) {
			throw new BadRequestHttpException ();
		}
		
		$em = $this->getDoctrine ()->getEntityManager ();
		$user = $em->getRepository ( "DFKIScorecardBundle:User" )->findOneById ( $userid );
		if (! is_object ( $user )) {
			throw new NotFoundHttpException ();
		}
		
		$em->remove ( $user );
		$em->flush ();
		
		$session = $req->getSession ();
		$msg = sprintf ( "You \"%s\" has been deleted.", $user->getName () );
		$session->getFlashBag ()->add ( 'notice', $msg );
		
		$em->flush ();
		
		return $this->redirect ( $this->generateUrl ( 'sc_manage_users' ), 301 );
	}
}
