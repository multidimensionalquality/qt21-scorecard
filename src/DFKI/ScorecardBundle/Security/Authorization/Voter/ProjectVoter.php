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
namespace DFKI\ScorecardBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter implements VoterInterface {
	const VIEW = 'view';
	const EDIT = 'edit';
	public function supportsAttribute($attribute) {
		return in_array ( $attribute, array (
				self::VIEW,
				self::EDIT 
		) );
	}
	public function supportsClass($class) {
		$supportedClass = 'DFKI\ScorecardBundle\Entity\Project';
		
		return $supportedClass === $class || is_subclass_of ( $class, $supportedClass );
	}
	
	/**
	 *
	 * @var DFKI\ScorecardBundle\Entity\Project $project
	 */
	public function vote(TokenInterface $token, $project, array $attributes) {
		// check if class of this object is supported by this voter
		if (! $this->supportsClass ( get_class ( $project ) )) {
			return VoterInterface::ACCESS_ABSTAIN;
		}
		
		// check if the voter is used correct, only allow one attribute
		// this isn't a requirement, it's just one easy way for you to
		// design your voter
		if (1 !== count ( $attributes )) {
			throw new \InvalidArgumentException ( 'Only one attribute is allowed for VIEW or EDIT' );
		}
		
		// set the attribute to check against
		$attribute = $attributes [0];
		
		// check if the given attribute is covered by this voter
		if (! $this->supportsAttribute ( $attribute )) {
			return VoterInterface::ACCESS_ABSTAIN;
		}
		
		// get current logged in user
		$user = $token->getUser ();
		
		// make sure there is a user object (i.e. that the user is logged in)
		if (! $user instanceof UserInterface) {
			return VoterInterface::ACCESS_DENIED;
		}
		
		$roles = $user->getRoles ();
		if ($roles [0] == "ROLE_SUPER_ADMIN") {
			return VoterInterface::ACCESS_GRANTED;
		} else {
			$found = false;
			for($i = 0; $i < sizeof ( $user->getProjects () ); $i ++) {
				$projects = $user->getProjects ();
				if ($projects [$i]->getId () == $project->getId ()) {
					$found = true;
					break;
				}
			}
			if (! $found) {
				return VoterInterface::ACCESS_DENIED;
			}
		}
		switch ($attribute) {
			case self::VIEW :
				return VoterInterface::ACCESS_GRANTED;
			
			case self::EDIT :
				foreach ( $user->getRoles () as $role ) {
					if ($role == "ROLE_ADMIN" || $role == "ROLE_SUPER_ADMIN")
						return VoterInterface::ACCESS_GRANTED;
				}
				return VoterInterface::ACCESS_DENIED;
		}
		
		return VoterInterface::ACCESS_DENIED;
	}
}
