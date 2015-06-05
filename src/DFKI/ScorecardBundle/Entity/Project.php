<?php

/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */
namespace DFKI\ScorecardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DFKI\ScorecardBundle\Entity\User;
use DFKI\ScorecardBundle\Entity\Segment;
use DFKI\ScorecardBundle\Entity\IssueProjectMapping;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DFKI\ScorecardBundle\Entity\ProjectRepository")
 */
class Project {
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;
	
	/**
	 *
	 * @var integer @ORM\Column(name="sourceWords", type="integer")
	 */
	private $sourceWords;
	
	/**
	 *
	 * @var integer @ORM\Column(name="targetWords", type="integer")
	 */
	private $targetWords;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="projects")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $createdBy;
	
	/**
	 *
	 * @var \DateTime @ORM\Column(name="createdOn", type="datetime")
	 */
	private $createdOn;
	
	/**
	 *
	 * @var \DateTime @ORM\Column(name="updatedOn", type="datetime")
	 */
	private $updatedOn;
	
	/**
	 *
	 * @var string @ORM\Column(name="fileName", type="string", length=255)
	 */
	private $fileName;
	
	/**
	 *
	 * @var string @ORM\Column(name="metricName", type="string", length=255)
	 */
	private $metricName;
	
	/**
	 *
	 * @var string @ORM\Column(name="specifications", type="text", nullable=true)
	 */
	private $specifications;
	
	/**
	 *
	 * @var string @ORM\Column(name="specificationsFileName", type="string", length=255, nullable=true)
	 */
	private $specificationsFileName;
	
	/**
	 * @ORM\OneToMany(targetEntity="Segment", mappedBy="project", cascade="remove")
	 */
	protected $segments;
	
	/**
	 * @ORM\ManyToMany(targetEntity="User")
	 * @ORM\JoinTable(name="users_projects",
	 * joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
	 * inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
	 * )
	 */
	private $users;
	
	/**
	 * @ORM\OneToMany(targetEntity="IssueProjectMapping", mappedBy="project", cascade="remove")
	 */
	protected $issueProjectMapping;

	
	/**
	 * @ORM\ManyToOne(targetEntity="Segment", inversedBy="projects", cascade="remove")
	 * @ORM\JoinColumn(name="lastTouchedSegment", referencedColumnName="id", nullable=true)
	 */
	protected $lastTouchedSegment;
	
	/**
	 *
	 * @var boolean @ORM\Column(name="finished", type="boolean")
	 */
	private $finished = false;
	
	/**
	 *
	 * @var float @ORM\Column(name="completion", type="float")
	 */
	private $completion = 0;
	
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
	 * @return Project
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
	 * Set sourceWords
	 *
	 * @param integer $sourceWords        	
	 * @return Project
	 */
	public function setSourceWords($sourceWords) {
		$this->sourceWords = $sourceWords;
		
		return $this;
	}
	
	/**
	 * Get sourceWords
	 *
	 * @return integer
	 */
	public function getSourceWords() {
		return $this->sourceWords;
	}
	
	/**
	 * Set targetWords
	 *
	 * @param integer $targetWords        	
	 * @return Project
	 */
	public function setTargetWords($targetWords) {
		$this->targetWords = $targetWords;
		
		return $this;
	}
	
	/**
	 * Get targetWords
	 *
	 * @return integer
	 */
	public function getTargetWords() {
		return $this->targetWords;
	}
	
	/**
	 * Set createdBy
	 *
	 * @param integer $createdBy        	
	 * @return Project
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		
		return $this;
	}
	
	/**
	 * Get createdBy
	 *
	 * @return integer
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}
	
	/**
	 * Set createdOn
	 *
	 * @param \DateTime $createdOn        	
	 * @return Project
	 */
	public function setCreatedOn($createdOn) {
		$this->createdOn = $createdOn;
		
		return $this;
	}
	
	/**
	 * Get createdOn
	 *
	 * @return \DateTime
	 */
	public function getCreatedOn() {
		return $this->createdOn;
	}
	
	/**
	 * Set updatedOn
	 *
	 * @param \DateTime $updatedOn        	
	 * @return Project
	 */
	public function setUpdatedOn($updatedOn) {
		$this->updatedOn = $updatedOn;
		
		return $this;
	}
	
	/**
	 * Get updatedOn
	 *
	 * @return \DateTime
	 */
	public function getUpdatedOn() {
		return $this->updatedOn;
	}
	
	/**
	 * Set metric
	 *
	 * @param string $metric        	
	 * @return Project
	 */
	public function setMetric($metric) {
		$this->metric = $metric;
		
		return $this;
	}
	
	/**
	 * Get metric
	 *
	 * @return string
	 */
	public function getMetric() {
		return $this->metric;
	}
	
	/**
	 * Set fileName
	 *
	 * @param string $fileName        	
	 * @return Project
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
		
		return $this;
	}
	
	/**
	 * Get fileName
	 *
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}
	
	/**
	 * Set metricName
	 *
	 * @param string $metricName        	
	 * @return Project
	 */
	public function setMetricName($metricName) {
		$this->metricName = $metricName;
		
		return $this;
	}
	
	/**
	 * Get metricName
	 *
	 * @return string
	 */
	public function getMetricName() {
		return $this->metricName;
	}
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->segments = new \Doctrine\Common\Collections\ArrayCollection ();
	}
	
	/**
	 * Add segments
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Segment $segments        	
	 * @return Project
	 */
	public function addSegment(\DFKI\ScorecardBundle\Entity\Segment $segments) {
		$this->segments [] = $segments;
		
		return $this;
	}
	
	/**
	 * Remove segments
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Segment $segments        	
	 */
	public function removeSegment(\DFKI\ScorecardBundle\Entity\Segment $segments) {
		$this->segments->removeElement ( $segments );
	}
	
	/**
	 * Get segments
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getSegments() {
		return $this->segments;
	}
	
	/**
	 * Add users
	 *
	 * @param \DFKI\ScorecardBundle\Entity\User $users        	
	 * @return Project
	 */
	public function addUser(\DFKI\ScorecardBundle\Entity\User $users) {
		$this->users [] = $users;
		
		return $this;
	}
	
	/**
	 * Remove users
	 *
	 * @param \DFKI\ScorecardBundle\Entity\User $users        	
	 */
	public function removeUser(\DFKI\ScorecardBundle\Entity\User $users) {
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
	
	/**
	 * Add issues
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Issue $issues        	
	 * @return Project
	 */
	public function addIssue(\DFKI\ScorecardBundle\Entity\Issue $issues) {
		$this->issues [] = $issues;
		
		return $this;
	}
	
	/**
	 * Remove issues
	 *
	 * @param \DFKI\ScorecardBundle\Entity\Issue $issues        	
	 */
	public function removeIssue(\DFKI\ScorecardBundle\Entity\Issue $issues) {
		$this->issues->removeElement ( $issues );
	}
	
	/**
	 * Get issues
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getIssues() {
		return $this->issues;
	}
	
	/**
	 * Set specifications
	 *
	 * @param string $specifications        	
	 * @return Project
	 */
	public function setSpecifications($specifications) {
		$this->specifications = $specifications;
		
		return $this;
	}
	
	/**
	 * Get specifications
	 *
	 * @return string
	 */
	public function getSpecifications() {
		return $this->specifications;
	}
	
	/**
	 * Set specificationsFileName
	 *
	 * @param string $specificationsFileName        	
	 * @return Project
	 */
	public function setSpecificationsFileName($specificationsFileName) {
		$this->specificationsFileName = $specificationsFileName;
		
		return $this;
	}
	
	/**
	 * Get specificationsFileName
	 *
	 * @return string
	 */
	public function getSpecificationsFileName() {
		return $this->specificationsFileName;
	}
	
	/**
	 * Add issueProjectMapping
	 *
	 * @param \DFKI\ScorecardBundle\Entity\IssueProjectMapping $issueProjectMapping        	
	 * @return Project
	 */
	public function addIssueProjectMapping(\DFKI\ScorecardBundle\Entity\IssueProjectMapping $issueProjectMapping) {
		$this->issueProjectMapping [] = $issueProjectMapping;
		
		return $this;
	}
	
	/**
	 * Remove issueProjectMapping
	 *
	 * @param \DFKI\ScorecardBundle\Entity\IssueProjectMapping $issueProjectMapping        	
	 */
	public function removeIssueProjectMapping(\DFKI\ScorecardBundle\Entity\IssueProjectMapping $issueProjectMapping) {
		$this->issueProjectMapping->removeElement ( $issueProjectMapping );
	}
	
	/**
	 * Get issueProjectMapping
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getIssueProjectMapping() {
		return $this->issueProjectMapping;
	}
	
	/**
	 * Set finished
	 *
	 * @param boolean $finished        	
	 * @return Project
	 */
	public function setFinished($finished) {
		$this->finished = $finished;
		
		return $this;
	}
	
	/**
	 * Get finished
	 *
	 * @return boolean
	 */
	public function getFinished() {
		return $this->finished;
	}
	
	/**
	 * Set float
	 *
	 * @param float $float        	
	 * @return Project
	 */
	public function setFloat($float) {
		$this->float = $float;
		
		return $this;
	}
	
	/**
	 * Get float
	 *
	 * @return float
	 */
	public function getFloat() {
		return $this->float;
	}
	
	/**
	 * Set completion
	 *
	 * @param float $completion        	
	 * @return Project
	 */
	public function setCompletion($completion) {
		$this->completion = $completion;
		
		return $this;
	}
	
	/**
	 * Get completion
	 *
	 * @return float
	 */
	public function getCompletion() {
		return $this->completion;
	}

    /**
     * Set lastTouchedSegment
     *
     * @param \DFKI\ScorecardBundle\Entity\Segment $lastTouchedSegment
     * @return Project
     */
    public function setLastTouchedSegment(\DFKI\ScorecardBundle\Entity\Segment $lastTouchedSegment = null)
    {
        $this->lastTouchedSegment = $lastTouchedSegment;

        return $this;
    }

    /**
     * Get lastTouchedSegment
     *
     * @return \DFKI\ScorecardBundle\Entity\Segment 
     */
    public function getLastTouchedSegment()
    {
        return $this->lastTouchedSegment;
    }
}
