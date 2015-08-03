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

namespace DFKI\ScorecardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DFKI\ScorecardBundle\Entity\Segment;

/**
 * SegmentMetadata
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SegmentMetadata {
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="text", type="string", length=500)
	 */
	private $text;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Segment")
	 * @ORM\JoinColumn(name="segment", referencedColumnName="id")
	 */
	private $segment;
	
	/**
	 * @ORM\ManyToOne(targetEntity="SegmentMetadataCategory")
	 * @ORM\JoinColumn(name="category", referencedColumnName="id")
	 */
	protected $category;
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set text
	 *
	 * @param string $text        	
	 * @return SegmentMetadata
	 */
	public function setText($text) {
		$this->text = $text;
		
		return $this;
	}
	
	/**
	 * Get text
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * Set issue
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Segment $issue        	
	 * @return SegmentMetadata
	 */
	public function setIssue(\DFKI\ScorecardBundle\Entity\Segment $issue = null) {
		$this->issue = $issue;
		
		return $this;
	}
	
	/**
	 * Get issue
	 *
	 * @return \DFKI\ScorecardBundle\Entity\Segment
	 */
	public function getIssue() {
		return $this->issue;
	}
	
	/**
	 * Set segment
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Segment $segment        	
	 * @return SegmentMetadata
	 */
	public function setSegment(\DFKI\ScorecardBundle\Entity\Segment $segment = null) {
		$this->segment = $segment;
		
		return $this;
	}
	
	/**
	 * Get segment
	 *
	 * @return \DFKI\ScorecardBundle\Entity\Segment
	 */
	public function getSegment() {
		return $this->segment;
	}
	
	/**
	 * Set category
	 *
	 * @param \DFKI\ScorecardBundle\Entity\SegmentMetadataCategory $category        	
	 * @return SegmentMetadata
	 */
	public function setCategory(\DFKI\ScorecardBundle\Entity\SegmentMetadataCategory $category = null) {
		$this->category = $category;
		
		return $this;
	}
	
	/**
	 * Get category
	 *
	 * @return \DFKI\ScorecardBundle\Entity\SegmentMetadataCategory
	 */
	public function getCategory() {
		return $this->category;
	}
}
