<?php

namespace ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="ContentBundle\Repository\ArticleRepository")
 */
class Article
{

    const ID_HOME = 1;
    
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
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="subhead", type="string", length=255, nullable=true)
     */
    private $subhead;

    /**
     * @var string
     *
     * @ORM\Column(name="chapo", type="text", length=65535, nullable=true)
     */
    private $chapo;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text", length=65535, nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=true)
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
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * @var \ContentBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="ContentBundle\Entity\Page")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;


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
     * @var DocumentContent
     * @ORM\OneToMany(targetEntity="ContentBundle\Entity\DocumentContent", mappedBy="article", cascade={"persist"})
     */
    private $document;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->document = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Article
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
     * @return Article
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
     * Set subhead
     *
     * @param string $subhead
     *
     * @return Article
     */
    public function setSubhead($subhead)
    {
        $this->subhead = $subhead;

        return $this;
    }

    /**
     * Get subhead
     *
     * @return string
     */
    public function getSubhead()
    {
        return $this->subhead;
    }

    /**
     * Set chapo
     *
     * @param string $chapo
     *
     * @return Article
     */
    public function setChapo($chapo)
    {
        $this->chapo = htmlspecialchars($chapo);

        return $this;
    }

    /**
     * Get chapo
     *
     * @return string
     */
    public function getChapo()
    {
        return htmlspecialchars_decode($this->chapo);
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Article
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
     * @return Article
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
     * @return Article
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
        return (boolean) $this->isActive;
    }

    /**
     * Set orderNbr
     *
     * @param integer $orderNbr
     *
     * @return Article
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Article
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Article
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
     * Set refUrl
     *
     * @param string $refUrl
     *
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * Set page
     *
     * @param \ContentBundle\Entity\Page $page
     *
     * @return Article
     */
    public function setPage(\ContentBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \ContentBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add document
     *
     * @param \ContentBundle\Entity\DocumentContent $document
     *
     * @return Article
     */
    public function addDocument(\ContentBundle\Entity\DocumentContent $document)
    {
        $this->document[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \ContentBundle\Entity\DocumentContent $document
     */
    public function removeDocument(\ContentBundle\Entity\DocumentContent $document)
    {
        $this->document->removeElement($document);
    }

    /**
     * Get document
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocument()
    {
        return $this->document;
    }
}
