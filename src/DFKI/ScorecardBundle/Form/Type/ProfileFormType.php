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
namespace DFKI\ScorecardBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		// add your custom field
		$builder->add ( 'name' );
		$builder->add ( 'plainPassword', 'repeated', array (
				'type' => 'password',
				'options' => array (
						'translation_domain' => 'FOSUserBundle' 
				),
				'first_options' => array (
						'label' => 'form.new_password' 
				),
				'second_options' => array (
						'label' => 'form.new_password_confirmation' 
				),
				'invalid_message' => 'fos_user.password.mismatch' 
		) );
		$builder->remove ( "current_password" );
	}
	public function getParent() {
		return 'fos_user_profile';
	}
	public function getName() {
		return 'dfki_user_profile';
	}
}