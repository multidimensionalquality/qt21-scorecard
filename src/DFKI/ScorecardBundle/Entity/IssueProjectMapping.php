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
use DFKI\ScorecardBundle\Entity\Project;
use DFKI\ScorecardBundle\Entity\Issue;

/**
 * IssueProjectMapping
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class IssueProjectMapping {
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Project")
	 * @ORM\JoinColumn(name="project", referencedColumnName="id")
	 */
	private $project;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Issue")
	 * @ORM\JoinColumn(name="issue", referencedColumnName="id")
	 */
	private $issue;
	
	/**
	 *
	 * @var boolean @ORM\Column(name="display", type="boolean")
	 */
	private $display;
	
	/**
	 * var float
	 *
	 * @ORM\Column(name="weight", type="float")
	 */
	private $weight = 1.0;
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name        	
	 * @return ChildMetric
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
	 * Set display
	 *
	 * @param boolean $display        	
	 * @return ChildMetric
	 */
	public function setDisplay($display) {
		$this->display = $display;
		
		return $this;
	}
	
	/**
	 * Get display
	 *
	 * @return boolean
	 */
	public function getDisplay() {
		return $this->display;
	}
	/**
	 * Constructor
	 */
	public function __construct() {
	}
	
	/**
	 * Set project
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Project $project        	
	 * @return Metric
	 */
	public function setProject(\DFKI\ScorecardBundle\Entity\Project $project = null) {
		$this->project = $project;
		
		return $this;
	}
	
	/**
	 * Get project
	 *
	 * @return \DFKI\ScorecardBundle\Entity\Project
	 */
	public function getProject() {
		return $this->project;
	}
	
	/**
	 * Set issue
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Issue $issue        	
	 * @return IssueProjectMapping
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
	
	/**
	 * Set weight
	 *
	 * @param float $weight        	
	 * @return IssueProjectMapping
	 */
	public function setWeight($weight) {
		$this->weight = $weight;
		
		return $this;
	}
	
	/**
	 * Get weight
	 *
	 * @return float
	 */
	public function getWeight() {
		return $this->weight;
	}
}
