<?php

namespace ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="ContentBundle\Repository\PageRepository")
 */
class Page
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
     * @ORM\Column(name="title", type="string", length=150, nullable=true)
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
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \ContentBundle\Entity\Rubrique
     *
     * @ORM\ManyToOne(targetEntity="ContentBundle\Entity\Rubrique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rubrique_id", referencedColumnName="id")
     * })
     */
    private $rubrique;


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
     * @ORM\OneToMany(targetEntity="DocumentContent", mappedBy="page", cascade={"persist"})
     */
    private $document;

    /**
     * @var Article
     * @ORM\OneToMany(targetEntity="Article", mappedBy="page", cascade={"persist"})
     */
    private $article;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->document = new \Doctrine\Common\Collections\ArrayCollection();
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Page
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
     * @return Page
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
     * Set chapo
     *
     * @param string $chapo
     *
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
        return $this->isActive;
    }

    /**
     * Set orderNbr
     *
     * @param integer $orderNbr
     *
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set rubrique
     *
     * @param \ContentBundle\Entity\Rubrique $rubrique
     *
     * @return Page
     */
    public function setRubrique(\ContentBundle\Entity\Rubrique $rubrique = null)
    {
        $this->rubrique = $rubrique;

        return $this;
    }

    /**
     * Get rubrique
     *
     * @return \ContentBundle\Entity\Rubrique
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }

    /**
     * Add document
     *
     * @param \ContentBundle\Entity\DocumentContent $document
     *
     * @return Page
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

    /**
     * Add article
     *
     * @param \ContentBundle\Entity\Article $article
     *
     * @return Page
     */
    public function addArticle(\ContentBundle\Entity\Article $article)
    {
        $this->article[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \ContentBundle\Entity\Article $article
     */
    public function removeArticle(\ContentBundle\Entity\Article $article)
    {
        $this->article->removeElement($article);
    }

    /**
     * Get article
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticle()
    {
        return $this->article;
    }
}
