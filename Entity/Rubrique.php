<?php

namespace ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rubrique
 *
 * @ORM\Table(name="rubrique")
 * @ORM\Entity(repositoryClass="ContentBundle\Repository\RubriqueRepository")
 */
class Rubrique
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=45, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text", nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="order_nbr", type="integer", nullable=true)
     */
    private $orderNbr;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;


    /**
     * @var string
     *
     * @ORM\Column(name="ref_url", type="string", length=255, nullable=true)
     */
    private $refUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_title", type="string", length=255, nullable=true)
     */
    private $refTitle;


    /**
     * @var string
     *
     * @ORM\Column(name="ref_summary", type="string", length=255, nullable=true)
     */
    private $refSummary;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_keywords", type="string", length=255, nullable=true)
     */
    private $refKeywords;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var DocumentContent
     * @ORM\OneToMany(targetEntity="ContentBundle\Entity\DocumentContent", mappedBy="rubrique", cascade={"persist"})
     */
    private $documentContent;

    /**
     * @var Page
     * @ORM\OneToMany(targetEntity="Page", mappedBy="rubrique", cascade={"persist"})
     */
    private $page;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentContent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->page = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     *
     * @return Rubrique
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Rubrique
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Rubrique
     */
    public function setSummary($summary)
    {
        $this->summary = htmlspecialchars($summary);

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return htmlspecialchars_decode($this->summary);
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Rubrique
     */
    public function setText($text)
    {
        $this->text = htmlspecialchars($text);

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return htmlspecialchars_decode($this->text);
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Rubrique
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return (boolean)$this->isActive;
    }

    /**
     * Set orderNbr
     *
     * @param integer $orderNbr
     *
     * @return Rubrique
     */
    public function setOrderNbr($orderNbr)
    {
        $this->orderNbr = $orderNbr;

        return $this;
    }

    /**
     * Get orderNbr
     *
     * @return integer
     */
    public function getOrderNbr()
    {
        return $this->orderNbr;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Rubrique
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Rubrique
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set refUrl
     *
     * @param string $refUrl
     *
     * @return Rubrique
     */
    public function setRefUrl($refUrl)
    {
        $this->refUrl = $refUrl;

        return $this;
    }

    /**
     * Get refUrl
     *
     * @return string
     */
    public function getRefUrl()
    {
        return $this->refUrl;
    }

    /**
     * Set refTitle
     *
     * @param string $refTitle
     *
     * @return Rubrique
     */
    public function setRefTitle($refTitle)
    {
        $this->refTitle = $refTitle;

        return $this;
    }

    /**
     * Get refTitle
     *
     * @return string
     */
    public function getRefTitle()
    {
        return $this->refTitle;
    }

    /**
     * Set refSummary
     *
     * @param string $refSummary
     *
     * @return Rubrique
     */
    public function setRefSummary($refSummary)
    {
        $this->refSummary = htmlspecialchars($refSummary);

        return $this;
    }

    /**
     * Get refSummary
     *
     * @return string
     */
    public function getRefSummary()
    {
        return htmlspecialchars_decode($this->refSummary);
    }

    /**
     * Set refKeywords
     *
     * @param string $refKeywords
     *
     * @return Rubrique
     */
    public function setRefKeywords($refKeywords)
    {
        $this->refKeywords = htmlspecialchars($refKeywords);

        return $this;
    }

    /**
     * Get refKeywords
     *
     * @return string
     */
    public function getRefKeywords()
    {
        return htmlspecialchars_decode($this->refKeywords);
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Rubrique
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add documentContent
     *
     * @param \ContentBundle\Entity\DocumentContent $documentContent
     *
     * @return Rubrique
     */
    public function addDocumentContent(\ContentBundle\Entity\DocumentContent $documentContent)
    {
        $this->documentContent[] = $documentContent;

        return $this;
    }

    /**
     * Remove documentContent
     *
     * @param \ContentBundle\Entity\DocumentContent $documentContent
     */
    public function removeDocumentContent(\ContentBundle\Entity\DocumentContent $documentContent)
    {
        $this->documentContent->removeElement($documentContent);
    }

    /**
     * Get documentContent
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentContent()
    {
        return $this->documentContent;
    }

    /**
     * Add page
     *
     * @param \ContentBundle\Entity\Page $page
     *
     * @return Rubrique
     */
    public function addPage(\ContentBundle\Entity\Page $page)
    {
        $this->page[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \ContentBundle\Entity\Page $page
     */
    public function removePage(\ContentBundle\Entity\Page $page)
    {
        $this->page->removeElement($page);
    }

    /**
     * Get page
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPage()
    {
        return $this->page;
    }
}
