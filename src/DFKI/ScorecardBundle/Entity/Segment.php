<?php

/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DFKI\ScorecardBundle\Entity\Project;
use DFKI\ScorecardBundle\Entity\IssueReport;
use DFKI\ScorecardBundle\Entity\SegmentMetadata;

/**
 * Segment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Segment {
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
	 *
	 * @var integer @ORM\Column(name="originalId", type="integer", nullable=true)
	 */
	private $originalId;
	
	/**
	 *
	 * @var integer @ORM\Column(name="segNum", type="integer")
	 */
	private $segNum;
	
	/**
	 *
	 * @var string @ORM\Column(name="source", type="text")
	 */
	private $source;
	
	/**
	 *
	 * @var string @ORM\Column(name="target", type="text")
	 */
	private $target;
	
	/**
	 *
	 * @var string @ORM\Column(name="comments", type="text")
	 */
	private $comments;
	
	/**
	 *
	 * @var string @ORM\Column(name="notes", type="text", nullable=true)
	 */
	private $notes;
	
	/**
	 * @ORM\OneToMany(targetEntity="IssueReport", mappedBy="segment", cascade="remove")
	 */
	protected $issueReports;
	
	/**
	 * @ORM\OneToMany(targetEntity="SegmentMetadata", mappedBy="segment", cascade="remove")
	 */
	protected $metadata;
	
	/**
	 *
	 * @var string @ORM\Column(name="highlightsSource", type="text", nullable=true)
	 */
	private $highlightsSource;
	
	/**
	 *
	 * @var string @ORM\Column(name="highlightsTarget", type="text", nullable=true)
	 */
	private $highlightsTarget;
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set project
	 *
	 * @param integer $project        	
	 * @return Segment
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
	 * Set originalId
	 *
	 * @param integer $originalId        	
	 * @return Segment
	 */
	public function setOriginalId($originalId) {
		$this->originalId = $originalId;
		
		return $this;
	}
	
	/**
	 * Get originalId
	 *
	 * @return integer
	 */
	public function getOriginalId() {
		return $this->originalId;
	}
	
	/**
	 * Set segNum
	 *
	 * @param integer $segNum        	
	 * @return Segment
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
	 * Set source
	 *
	 * @param string $source        	
	 * @return Segment
	 */
	public function setSource($source) {
		$this->source = $source;
		
		return $this;
	}
	
	/**
	 * Get source
	 *
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}
	
	/**
	 * Set target
	 *
	 * @param string $target        	
	 * @return Segment
	 */
	public function setTarget($target) {
		$this->target = $target;
		
		return $this;
	}
	
	/**
	 * Get target
	 *
	 * @return string
	 */
	public function getTarget() {
		return $this->target;
	}
	
	/**
	 * Set comments
	 *
	 * @param string $comments        	
	 * @return Segment
	 */
	public function setComments($comments) {
		$this->comments = $comments;
		
		return $this;
	}
	
	/**
	 * Get comments
	 *
	 * @return string
	 */
	public function getComments() {
		return $this->comments;
	}
	
	/**
	 * Set notes
	 *
	 * @param string $notes        	
	 * @return Segment
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
	 * Constructor
	 */
	public function __construct() {
		$this->issueReports = new \Doctrine\Common\Collections\ArrayCollection ();
	}
	
	/**
	 * Add issueReports
	 *
	 * @param \DFKI\ScorecardBundle\Entity\IssueReport $issueReports        	
	 * @return Segment
	 */
	public function addIssueReport(\DFKI\ScorecardBundle\Entity\IssueReport $issueReports) {
		$this->issueReports [] = $issueReports;
		
		return $this;
	}
	
	/**
	 * Remove issueReports
	 *
	 * @param \DFKI\ScorecardBundle\Entity\IssueReport $issueReports        	
	 */
	public function removeIssueReport(\DFKI\ScorecardBundle\Entity\IssueReport $issueReports) {
		$this->issueReports->removeElement ( $issueReports );
	}
	
	/**
	 * Get issueReports
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getIssueReports() {
		return $this->issueReports;
	}
	
	/**
	 * Set highlights
	 *
	 * @param string $highlights        	
	 * @return Segment
	 */
	public function setHighlights($highlights) {
		$this->highlights = $highlights;
		
		return $this;
	}
	
	/**
	 * Set highlightsSource
	 *
	 * @param string $highlightsSource        	
	 * @return Segment
	 */
	public function setHighlightsSource($highlightsSource) {
		$this->highlightsSource = $highlightsSource;
		
		return $this;
	}
	
	/**
	 * Get highlightsSource
	 *
	 * @return string
	 */
	public function getHighlightsSource() {
		return $this->highlightsSource;
	}
	
	/**
	 * Set highlightsTarget
	 *
	 * @param string $highlightsTarget        	
	 * @return Segment
	 */
	public function setHighlightsTarget($highlightsTarget) {
		$this->highlightsTarget = $highlightsTarget;
		
		return $this;
	}
	
	/**
	 * Get highlightsTarget
	 *
	 * @return string
	 */
	public function getHighlightsTarget() {
		return $this->highlightsTarget;
	}
	
	/**
	 * Add metadata
	 *
	 * @param \DFKI\ScorecardBundle\Entity\SegmentMetadata $metadata        	
	 * @return Segment
	 */
	public function addMetadatum(\DFKI\ScorecardBundle\Entity\SegmentMetadata $metadata) {
		$this->metadata [] = $metadata;
		
		return $this;
	}
	
	/**
	 * Remove metadata
	 *
	 * @param \DFKI\ScorecardBundle\Entity\SegmentMetadata $metadata        	
	 */
	public function removeMetadatum(\DFKI\ScorecardBundle\Entity\SegmentMetadata $metadata) {
		$this->metadata->removeElement ( $metadata );
	}
	
	/**
	 * Get metadata
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getMetadata() {
		return $this->metadata;
	}
}
