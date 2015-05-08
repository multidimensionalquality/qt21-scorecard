<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */

namespace DFKI\ScorecardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DFKI\ScorecardBundle\Entity\IssueProjectMapping;
use DFKI\ScorecardBundle\Entity\Issue;
use DFKI\ScorecardBundle\Entity\Segment;

/**
 * IssueReport
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class IssueReport
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="IssueProjectMapping")
     * @ORM\JoinColumn(name="issueProjectMapping", referencedColumnName="id")
     */
    private $issueProjectMapping;
    
    /**
     * @ORM\ManyToOne(targetEntity="Issue")
     * @ORM\JoinColumn(name="issue", referencedColumnName="id")
     */
    private $issue;

    /**
     * @var string
     *
     * @ORM\Column(name="severity", type="string", length=255)
     */
    private $severity;

    /**
     * @ORM\ManyToOne(targetEntity="Segment")
     * @ORM\JoinColumn(name="segment", referencedColumnName="id")
     */
    private $segment;

    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string", length=255)
     */
    private $side;
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Issue
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set severity
     *
     * @param string $severity
     * @return Issue
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * Get severity
     *
     * @return string 
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Set segNum
     *
     * @param integer $segNum
     * @return Issue
     */
    public function setSegNum($segNum)
    {
        $this->segNum = $segNum;

        return $this;
    }

    /**
     * Get segNum
     *
     * @return integer 
     */
    public function getSegNum()
    {
        return $this->segNum;
    }

    /**
     * Set side
     *
     * @param string $side
     * @return Issue
     */
    public function setSide($side)
    {
        $this->side = $side;

        return $this;
    }

    /**
     * Get side
     *
     * @return string 
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * Set issue
     *
     * @param \DFKI\ScorecardBundle\Entity\Issue $issue
     * @return IssueReport
     */
    public function setIssue(\DFKI\ScorecardBundle\Entity\Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return \DFKI\ScorecardBundle\Entity\Issue 
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set segment
     *
     * @param \DFKI\ScorecardBundle\Entity\Segment $segment
     * @return IssueReport
     */
    public function setSegment(\DFKI\ScorecardBundle\Entity\Segment $segment = null)
    {
        $this->segment = $segment;

        return $this;
    }

    /**
     * Get segment
     *
     * @return \DFKI\ScorecardBundle\Entity\Segment 
     */
    public function getSegment()
    {
        return $this->segment;
    }

    /**
     * Set project
     *
     * @param \DFKI\ScorecardBundle\Entity\Project $project
     * @return IssueReport
     */
    public function setProject(\DFKI\ScorecardBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \DFKI\ScorecardBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return IssueReport
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set issueReport
     *
     * @param \DFKI\ScorecardBundle\Entity\IssueReport $issueReport
     * @return IssueReport
     */
    public function setIssueReport(\DFKI\ScorecardBundle\Entity\IssueReport $issueReport = null)
    {
        $this->issueReport = $issueReport;

        return $this;
    }

    /**
     * Get issueReport
     *
     * @return \DFKI\ScorecardBundle\Entity\IssueReport 
     */
    public function getIssueReport()
    {
        return $this->issueReport;
    }

    /**
     * Set issueReports
     *
     * @param \DFKI\ScorecardBundle\Entity\IssueReport $issueReports
     * @return IssueReport
     */
    public function setIssueReports(\DFKI\ScorecardBundle\Entity\IssueReport $issueReports = null)
    {
        $this->issueReports = $issueReports;

        return $this;
    }

    /**
     * Get issueReports
     *
     * @return \DFKI\ScorecardBundle\Entity\IssueReport 
     */
    public function getIssueReports()
    {
        return $this->issueReports;
    }

    /**
     * Set issues
     *
     * @param \DFKI\ScorecardBundle\Entity\Issue $issues
     * @return IssueReport
     */
    public function setIssues(\DFKI\ScorecardBundle\Entity\Issue $issues = null)
    {
        $this->issues = $issues;

        return $this;
    }

    /**
     * Get issues
     *
     * @return \DFKI\ScorecardBundle\Entity\Issue 
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Set issueProjectMapping
     *
     * @param \DFKI\ScorecardBundle\Entity\IssueProjectMapping $issueProjectMapping
     * @return IssueReport
     */
    public function setIssueProjectMapping(\DFKI\ScorecardBundle\Entity\IssueProjectMapping $issueProjectMapping = null)
    {
        $this->issueProjectMapping = $issueProjectMapping;

        return $this;
    }

    /**
     * Get issueProjectMapping
     *
     * @return \DFKI\ScorecardBundle\Entity\IssueProjectMapping 
     */
    public function getIssueProjectMapping()
    {
        return $this->issueProjectMapping;
    }
}
