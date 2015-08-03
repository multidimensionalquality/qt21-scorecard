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
namespace DFKI\ScorecardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issue
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Issue {
	/**
	 *
	 * @var string @ORM\Column(name="id", type="string", length=255)
	 *      @ORM\Id
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="name", type="text")
	 */
	private $name;
	
	/**
	 *
	 * @var string @ORM\Column(name="definition", type="text")
	 */
	private $definition;
	
	/**
	 *
	 * @var string @ORM\Column(name="notes", type="text")
	 */
	private $notes;
	
	/**
	 *
	 * @var string @ORM\Column(name="examples", type="text")
	 */
	private $examples;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Issue" )
	 * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true, onDelete="CASCADE" )
	 */
	private $parent;
	
	/**
	 * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent" )
	 */
	private $children;
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set type
	 *
	 * @param string $type        	
	 * @return Issue
	 */
	public function setType($type) {
		$this->type = $type;
		
		return $this;
	}
	
	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Set severity
	 *
	 * @param string $severity        	
	 * @return Issue
	 */
	public function setSeverity($severity) {
		$this->severity = $severity;
		
		return $this;
	}
	
	/**
	 * Get severity
	 *
	 * @return string
	 */
	public function getSeverity() {
		return $this->severity;
	}
	
	/**
	 * Set project
	 *
	 * @param integer $project        	
	 * @return Issue
	 */
	public function setProject($project) {
		$this->project = $project;
		
		return $this;
	}
	
	/**
	 * Get project
	 *
	 * @return integer
	 */
	public function getProject() {
		return $this->project;
	}
	
	/**
	 * Set segNum
	 *
	 * @param integer $segNum        	
	 * @return Issue
	 */
	public function setSegNum($segNum) {
		$this->segNum = $segNum;
		
		return $this;
	}
	
	/**
	 * Get segNum
	 *
	 * @return integer
	 */
	public function getSegNum() {
		return $this->segNum;
	}
	
	/**
	 * Set side
	 *
	 * @param string $side        	
	 * @return Issue
	 */
	public function setSide($side) {
		$this->side = $side;
		
		return $this;
	}
	
	/**
	 * Get side
	 *
	 * @return string
	 */
	public function getSide() {
		return $this->side;
	}
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->children = new \Doctrine\Common\Collections\ArrayCollection ();
	}
	
	/**
	 * Set id
	 *
	 * @param string $id        	
	 * @return Issue
	 */
	public function setId($id) {
		$this->id = $id;
		
		return $this;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name        	
	 * @return Issue
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
	 * Set description
	 *
	 * @param string $description        	
	 * @return Issue
	 */
	public function setDescription($description) {
		$this->description = $description;
		
		return $this;
	}
	
	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * Set notes
	 *
	 * @param string $notes        	
	 * @return Issue
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
		
		return $this;
	}
	
	/**
	 * Get notes
	 *
	 * @return string
	 */
	public function getNotes() {
		return $this->notes;
	}
	
	/**
	 * Set examples
	 *
	 * @param string $examples        	
	 * @return Issue
	 */
	public function setExamples($examples) {
		$this->examples = $examples;
		
		return $this;
	}
	
	/**
	 * Get examples
	 *
	 * @return string
	 */
	public function getExamples() {
		return $this->examples;
	}
	
	/**
	 * Set parent
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Issue $parent        	
	 * @return Issue
	 */
	public function setParent(\DFKI\ScorecardBundle\Entity\Issue $parent = null) {
		$this->parent = $parent;
		
		return $this;
	}
	
	/**
	 * Get parent
	 *
	 * @return \DFKI\ScorecardBundle\Entity\Issue
	 */
	public function getParent() {
		return $this->parent;
	}
	
	/**
	 * Add children
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Issue $children        	
	 * @return Issue
	 */
	public function addChild(\DFKI\ScorecardBundle\Entity\Issue $children) {
		$this->children [] = $children;
		
		return $this;
	}
	
	/**
	 * Remove children
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Issue $children        	
	 */
	public function removeChild(\DFKI\ScorecardBundle\Entity\Issue $children) {
		$this->children->removeElement ( $children );
	}
	
	/**
	 * Get children
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * Set definition
	 *
	 * @param string $definition        	
	 * @return Issue
	 */
	public function setDefinition($definition) {
		$this->definition = $definition;
		
		return $this;
	}
	
	/**
	 * Get definition
	 *
	 * @return string
	 */
	public function getDefinition() {
		return $this->definition;
	}
	
	/**
	 * Set issue
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Issue $issue        	
	 * @return Issue
	 */
	public function setIssue(\DFKI\ScorecardBundle\Entity\Issue $issue = null) {
		$this->issue = $issue;
		
		return $this;
	}
	
	/**
	 * Get issue
	 *
	 * @return \DFKI\ScorecardBundle\Entity\Issue
	 */
	public function getIssue() {
		return $this->issue;
	}
}
