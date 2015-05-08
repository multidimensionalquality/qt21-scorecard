<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */

namespace DFKI\ScorecardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DFKI\ScorecardBundle\Form\Login;
use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class UserController extends Controller {
	
	/**
	 * redirect to start page according to user role
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function indexAction(){
		
		$securityContext = $this->get("security.context");
		
		if( $securityContext->isGranted("ROLE_SUPER_ADMIN")){
			return $this->redirect($this->generateUrl('sc_superadmin_dashboard'), 301);
		} else if( $securityContext->isGranted("ROLE_ADMIN")){
			return $this->redirect($this->generateUrl('sc_admin_dashboard'), 301);
		} else if( $securityContext->isGranted("ROLE_USER")){
			return $this->redirect($this->generateUrl('sc_list_projects'), 301);
		} else{
			return $this->redirect($this->generateUrl('fos_user_security_login'), 301);
		}		
	}
	
	/**
	 * list projects for user, admin and superadmin
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listProjectsAction(){

		$em    = $this->get('doctrine.orm.entity_manager');
		
		$securityContext = $this->get("security.context");
		$query = null;
		if( $securityContext->isGranted("ROLE_SUPER_ADMIN")){
			$query = $em->createQuery("SELECT p FROM DFKIScorecardBundle:Project p");
		} else{
			$query = $em->createQuery(
				"SELECT p FROM DFKIScorecardBundle:Project p
					LEFT JOIN p.users u
					WHERE u = :user" )
				->setParameter("user", $this->getUser());
		}
		
		$request = Request::createFromGlobals ();
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
				$query,
				$request->query->get('page', 1)/*page number*/,
				10/*limit per page*/
		);
		
		$actions = array(
				"open" => true,
				"edit" => true,
				"assign-user" => true,
				"delete" => true
		);
		
		return $this->render ( 'DFKIScorecardBundle:User:list_projects.html.twig', array (
				"pagination" => $pagination,
				"actions" => $actions
		) );
	}
	
	/**
	 * show edit user form
	 */
	public function editProfileAction(){
		$user = $this->getUser();
		
		$form = $this->createFormBuilder($user)
		->add('name', 'text')
		->add('email', 'email')
		->add('password', 'repeated', array(
		    'type' => 'password',
		    'invalid_message' => 'The password fields must match.',
		    'options' => array('attr' => array('class' => 'password-field')),
		    'required' => true,
		    'first_options'  => array('label' => 'Password'),
		    'second_options' => array('label' => 'Repeat Password'),
		))
		->add('save', 'submit', array('label' => 'save'))
		->getForm();
		
		return $this->render('DFKIScorecardBundle:User:profile.html.twig', array(
				'form' => $form->createView(),
		));
	}
}
