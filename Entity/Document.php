<?php

namespace ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File;

/**
 * Document
 *
 * @ORM\Table(name="document", indexes={
 *     @ORM\Index(name="fk_document_rubrique1_idx", columns={"rubrique_id"}),
 *     @ORM\Index(name="fk_document_page1_idx", columns={"page_id"}),
 *     @ORM\Index(name="fk_document_article1_idx", columns={"article_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Document
{

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    public $endPath;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_logo", type="boolean", nullable=false)
     */
    private $isLogo = '0';

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
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    public $descriptif;


    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->updateAt = new \DateTime();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var \ContentBundle\Entity\Rubrique
     *
     *  @ORM\ManyToOne(targetEntity="Rubrique", inversedBy="document")
     *  @ORM\JoinColumn(name="rubrique_id", referencedColumnName="id")
     */

    private $rubrique;

    /**
     * @var \ContentBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="ContentBundle\Entity\Page", inversedBy="document")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;

    /**
     * @var \ContentBundle\Entity\Article
     *
     * @ORM\ManyToOne(targetEntity="ContentBundle\Entity\Article", inversedBy="document")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     * })
     */
    private $article;
    

    public function getUploadRootDir()
    {
        return __dir__.'/../../../web/css/img/img-user/'.$this->getEndPath();
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getAssetPath($endpath)
    {
        if (empty($endpath)) {
            $endpath = $this->getEndPath();
        }

        // if (null !== $this->path) {
        return 'css/img/img-user/'.$endpath.'/'.$this->path;
        // } else {
        //     return "bundles/asset/img/courrier.png";
        // }

    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        $this->tempFile = $this->getAbsolutePath();
        $this->oldFile = $this->getPath();
        $this->updatedAt = new \DateTime();

        if (null !== $this->file) {}
//            var_dump($this->file->guessExtension());exit();
        if (!empty($this->file)) {
            $this->path = sha1(uniqid(mt_rand(),true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
//        // la propriété « file » peut être vide si le champ n'est pas requis
//        if (null === $this->file) {
//            return;
//        }
//
//        $file_name = $this->file->getClientOriginalName();
//
//        // la méthode « move » prend comme arguments le répertoire cible et
//        // le nom de fichier cible où le fichier doit être déplacé
//        if (!file_exists($this->getUploadRootDir())) {
//            mkdir($this->getUploadRootDir(), 0775, true);
//        }
//        $this->file->move(
//            $this->getUploadRootDir(), $file_name
//        );
//        $this->file = null;

        if (null !== $this->file) {
            if(!is_dir($this->getUploadRootDir())) {
                mkdir($this->getUploadRootDir(), 0777, true);
            }
            $this->file->move($this->getUploadRootDir(), $this->path);
            unset($this->file);

//            if ($this->oldFile != null) unlink($this->tempFile);
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempFile = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (file_exists($this->tempFile)) unlink($this->tempFile);
    }


    /**
     * Set isLogo
     *
     * @param boolean $isLogo
     *
     * @return Document
     */
    public function setIsLogo($isLogo)
    {
        $this->isLogo = $isLogo;

        return $this;
    }

    /**
     * Get isLogo
     *
     * @return boolean
     */
    public function getIsLogo()
    {
        return (boolean) $this->isLogo;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Document
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
     * @return Document
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set page
     *
     * @param \ContentBundle\Entity\Page $page
     *
     * @return Document
     */
    public function setPage(\ContentBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }



    /**
     * Set rubrique
     *
     * @param \ContentBundle\Entity\Rubrique $rubrique
     *
     * @return Document
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
     * Set name
     *
     * @param string $name
     *
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Set article
     *
     * @param \ContentBundle\Entity\Article $article
     *
     * @return Document
     */
    public function setArticle(\ContentBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \ContentBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set descriptif
     *
     * @param string $descriptif
     *
     * @return Document
     */
    public function setDescriptif($descriptif)
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * Get descriptif
     *
     * @return string
     */
    public function getDescriptif()
    {
        return $this->descriptif;
    }
    


    /**
     * Sets file.
     *
     * @param File\UploadedFile $file
     */
    public function setFile(File\UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * Set endPath
     *
     * @param string $endPath
     *
     * @return String
     */
    public function setEndPath($endPath = null)
    {
        $this->endPath = $endPath;

        return $this;
    }

    /**
     * Get endPath
     *
     * @return string
     */
    public function getEndPath()
    {
        return $this->endPath;
    }
}
