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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserRestController extends Controller {
	
	/**
	 * search users by keyword
	 *
	 * @Route("/users/search/{search}", name="rest_search")
	 * 
	 * @param unknown $search        	
	 * @return unknown
	 */
	public function getUserAction($search) {
		if (! $this->get ( "security.context" )->isGranted ( 'ROLE_ADMIN' )) {
			throw new AccessDeniedException ( 'Unauthorised access!' );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		$users = $em->getRepository ( "DFKIScorecardBundle:User" )->createQueryBuilder ( 'u' )->where ( 'u.username LIKE :search' )->orWhere ( 'u.name LIKE :search' )->orWhere ( 'u.email LIKE :search' )->setParameter ( 'search', "%$search%" )->setMaxResults ( 20 )->setFirstResult ( 0 )->getQuery ()->getResult ();
		return $users;
	}
	
	/**
	 * set role for user.
	 * set role as get variable ?role=ROLE_ADMIN
	 *
	 * @Route("/users/{userid}/setrole", name="rest_search")
	 */
	public function putUserRoleAction($userid) {
		if (! $this->get ( "security.context" )->isGranted ( 'ROLE_SUPER_ADMIN' )) {
			throw new AccessDeniedException ( 'Unauthorised access!' );
		}
		
		$request = Request::createFromGlobals ();
		$role = $request->query->get ( "role" );
		
		if ($role == null || ! ($role == "ROLE_USER" || $role == "ROLE_ADMIN" || $role == "ROLE_SUPER_ADMIN")) {
			
			throw new HttpException ( 400, "invalid role" );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		$user = $em->getRepository ( "DFKIScorecardBundle:User" )->findOneById ( $userid );
		if (! is_object ( $user )) {
			throw new HttpException ( 404, 'user not found' );
		}
		
		$user->setRoles ( array (
				$role 
		) );
		$em->persist ( $user );
		$em->flush ();
		
		return $user;
	}
}