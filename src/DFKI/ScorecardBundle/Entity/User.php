<?php

/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use DFKI\ScorecardBundle\Entity\Project;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * @ExclusionPolicy("all")
 */
class User extends BaseUser {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Expose
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Project", mappedBy="users")
	 */
	protected $projects;
	
	/**
	 * @ORM\Column(type="string")
	 * @Expose
	 */
	protected $name;
	public function __construct() {
		parent::__construct ();
		// your own logic
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Add projects
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Project $projects        	
	 * @return User
	 */
	public function addProject(\DFKI\ScorecardBundle\Entity\Project $projects) {
		$this->projects [] = $projects;
		
		return $this;
	}
	
	/**
	 * Remove projects
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Project $projects        	
	 */
	public function removeProject(\DFKI\ScorecardBundle\Entity\Project $projects) {
		$this->projects->removeElement ( $projects );
	}
	
	/**
	 * Get projects
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getProjects() {
		return $this->projects;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name        	
	 * @return User
	 */
	public function setName($name) {
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Add users
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Project $users        	
	 * @return User
	 */
	public function addUser(\DFKI\ScorecardBundle\Entity\Project $users) {
		$this->users [] = $users;
		
		return $this;
	}
	
	/**
	 * Remove users
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Project $users        	
	 */
	public function removeUser(\DFKI\ScorecardBundle\Entity\Project $users) {
		$this->users->removeElement ( $users );
	}
	
	/**
	 * Get users
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getUsers() {
		return $this->users;
	}
}
