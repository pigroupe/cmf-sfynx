<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category   Gedmo_Entities
 * @package    Entity
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plugins\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use BootStrap\TranslationBundle\Model\AbstractDefault;

/**
 * Plugins\ContentBundle\Entity\BlocGeneral
 * @ORM\Table(name="cont_bloc_general")
 * @ORM\Entity(repositoryClass="Plugins\ContentBundle\Repository\BlocGeneralRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Plugins\ContentBundle\Entity\Translation\BlocGeneralTranslation")
 *
 * @category   plugins_Entities
 * @package    Entity
 * 
 */
class BlocGeneral extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields    = array('title', 'slug', 'meta_keywords', 'meta_description', 'content', 'descriptif');

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Plugins\ContentBundle\Entity\Translation\BlocGeneralTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Plugins\ContentBundle\Entity\Translation\BlocGeneralTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;    

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $media
     *
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Entity\Media" , inversedBy="blocgeneral");
     * @ORM\JoinColumn(name="gedmo_media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;    
    
    /**
     * @ORM\OneToOne(targetEntity="Plugins\ContentBundle\Entity\Article", mappedBy="blocgeneral");
     */
    protected $article; 
    
    /**
     * @ORM\OneToOne(targetEntity="Plugins\ContentBundle\Entity\Diaporama", mappedBy="blocgeneral");
     */
    protected $diaporama; 
    
    /**
     * @ORM\OneToOne(targetEntity="Plugins\ContentBundle\Entity\Page", mappedBy="blocgeneral");
     */
    protected $page; 
    
    /**
     * @ORM\OneToOne(targetEntity="Plugins\ContentBundle\Entity\Test", mappedBy="blocgeneral");
     */
    protected $test; 

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Plugins\ContentBundle\Entity\Rub")
     * @ORM\JoinTable(name="cont_rub_related",
     *   joinColumns={@ORM\JoinColumn(name="blocgeneral_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="rub_id", referencedColumnName="id")}
     * )
     */
    private $rub;
    
    /**
     * @var \Plugins\ContentBundle\Entity\Rub $mainrub
     * 
     * @ORM\ManyToOne(targetEntity="Plugins\ContentBundle\Entity\Rub", inversedBy="blocgeneral")
     * @ORM\JoinColumn(name="mainrub_id", referencedColumnName="id", nullable=true)
     * 
     */
    protected $mainrub;
    
    /**
     * @var \Plugins\ContentBundle\Entity\Rub $subrub
     * 
     * @ORM\ManyToOne(targetEntity="Plugins\ContentBundle\Entity\Rub", inversedBy="blocgeneral2")
     * @ORM\JoinColumn(name="subrub_id", referencedColumnName="id", nullable=true)
     * 
     */
    protected $subrub;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $tag
     *
     * @ORM\ManyToMany(targetEntity="Plugins\ContentBundle\Entity\Tag", inversedBy="blocgeneral")
     * @ORM\JoinTable(name="cont_tag_blocgeneral",
     *      joinColumns={@ORM\JoinColumn(name="blocgeneral_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="gedmo_tag_id", referencedColumnName="id")}
     * )
     */
    private $tag;

    /**
     * @var string $title
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=128, nullable=true)
     */
    private $title;

    /**
     * @var \BootStrap\UserBundle\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="BootStrap\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     *
     */
    private $user;

    /**
     * @var string $author
     * @ORM\Column(name="author", type="string", length=128, nullable=true)
     */
    private $author;

    /**
     * @var string $descriptif
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    private $descriptif;

    /**
     * @var string $content
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     * 
     */
    private $content;

    /**
     * @var string $slug
     * @Gedmo\Translatable
     * @ORM\Column(name="slug", type="string", nullable=false)
     * @Gedmo\Slug(separator="-", fields={"title", "id"})
     */
    private $slug;

    /**
     * @var boolean $isvisiblediapo
     *
     * @ORM\Column(name="isVisibleDiapo", type="boolean", nullable=true)
     */
    private $isvisiblediapo;

    /**
     * @var boolean $isvisiblecarr
     *
     * @ORM\Column(name="isVisibleCarr", type="boolean", nullable=true)
     */
    private $isvisiblecarr;

    /**
     * @var boolean $isfavorite
     *
     * @ORM\Column(name="isFavorite", type="boolean", nullable=true)
     */
    private $isfavorite;
    
    /**
     * @var string $metaKeywords
     * @Gedmo\Translatable
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string $metaDescription
     * @Gedmo\Translatable
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;
    
    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=128, nullable=true)
     */
    protected $url;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rub = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->getId();
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
     * @return BlocGeneral
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
     * Set descriptif
     *
     * @param string $descriptif
     * @return BlocGeneral
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
     * Set content
     *
     * @param string $content
     * @return BlocGeneral
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return BlocGeneral
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set isvisiblediapo
     *
     * @param boolean $isvisiblediapo
     * @return BlocGeneral
     */
    public function setIsvisiblediapo($isvisiblediapo)
    {
        $this->isvisiblediapo = $isvisiblediapo;
    
        return $this;
    }

    /**
     * Get isvisiblediapo
     *
     * @return boolean 
     */
    public function getIsvisiblediapo()
    {
        return $this->isvisiblediapo;
    }

    /**
     * Set isvisiblecarr
     *
     * @param boolean $isvisiblecarr
     * @return BlocGeneral
     */
    public function setIsvisiblecarr($isvisiblecarr)
    {
        $this->isvisiblecarr = $isvisiblecarr;
    
        return $this;
    }

    /**
     * Get isvisiblecarr
     *
     * @return boolean 
     */
    public function getIsvisiblecarr()
    {
        return $this->isvisiblecarr;
    }

    /**
     * Set isfavorite
     *
     * @param boolean $isfavorite
     * @return BlocGeneral
     */
    public function setIsFavorite($isfavorite)
    {
        $this->isfavorite = $isfavorite;
    
        return $this;
    }

    /**
     * Get isfavorite
     *
     * @return boolean 
     */
    public function getIsFavorite()
    {
        return $this->isfavorite;
    }
    
    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return BlocGeneral
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return BlocGeneral
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    
        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set media
     *
     * @param \PiApp\GedmoBundle\Entity\Media $media
     * @return BlocGeneral
     */
    public function setMedia(\PiApp\GedmoBundle\Entity\Media $media = null)
    {
        $this->media = $media;
    
        return $this;
    }

    /**
     * Get media
     *
     * @return \PiApp\GedmoBundle\Entity\Media 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set article
     *
     * @param Plugins\ContentBundle\Entity\Article $article
     * @return BlocGeneral
     */
    public function setArticle(\Plugins\ContentBundle\Entity\Article $article = null)
    {
        $this->article = $article;
    
        return $this;
    }

    /**
     * Get article
     *
     * @return Plugins\ContentBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set diaporama
     *
     * @param Plugins\ContentBundle\Entity\Diaporama $diaporama
     * @return BlocGeneral
     */
    public function setDiaporama(\Plugins\ContentBundle\Entity\Diaporama $diaporama = null)
    {
        $this->diaporama = $diaporama;
    
        return $this;
    }

    /**
     * Get diaporama
     *
     * @return Plugins\ContentBundle\Entity\Diaporama 
     */
    public function getDiaporama()
    {
        return $this->diaporama;
    }

    /**
     * Set page
     *
     * @param Plugins\ContentBundle\Entity\Page $page
     * @return BlocGeneral
     */
    public function setPage(\Plugins\ContentBundle\Entity\Page $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return Plugins\ContentBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set test
     *
     * @param Plugins\ContentBundle\Entity\Test $test
     * @return BlocGeneral
     */
    public function setTest(\Plugins\ContentBundle\Entity\Test $test = null)
    {
        $this->test = $test;
    
        return $this;
    }

    /**
     * Get test
     *
     * @return Plugins\ContentBundle\Entity\Test 
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Add rub
     *
     * @param Plugins\ContentBundle\Entity\Rub $rub
     * @return BlocGeneral
     */
    public function addRub(\Plugins\ContentBundle\Entity\Rub $rub)
    {
        $this->rub[] = $rub;
    
        return $this;
    }

    /**
     * Remove rub
     *
     * @param Plugins\ContentBundle\Entity\Rub $rub
     */
    public function removeRub(\Plugins\ContentBundle\Entity\Rub $rub)
    {
        $this->rub->removeElement($rub);
    }

    /**
     * Get rub
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRub()
    {
        return $this->rub;
    }

//    /**
//     * Set mainrub
//     *
//     * @param Plugins\ContentBundle\Entity\Rub $mainrub
//     * @return BlocGeneral
//     */
//    public function setMainrub(\Plugins\ContentBundle\Entity\Rub $mainrub = null)
//    {
//        $this->mainrub = $mainrub;
//    
//        return $this;
//    }

    /**
     * Get mainrub
     *
     * @return \Plugins\ContentBundle\Entity\Rub 
     */
    public function getMainrub()
    {
        return $this->mainrub;
    }

    /**
     * Set subrub
     *
     * @param \Plugins\ContentBundle\Entity\Rub $subrub
     * @return BlocGeneral
     */
    public function setSubrub(\Plugins\ContentBundle\Entity\Rub $subrub = null)
    {
        $this->subrub = $subrub;
        if ($subrub instanceof \Plugins\ContentBundle\Entity\Rub){
            $this->mainrub = $subrub->getParent();
        }
        return $this;
    }

    /**
     * Get subrub
     *
     * @return Plugins\ContentBundle\Entity\Rub 
     */
    public function getSubrub()
    {
        return $this->subrub;
    }

    /**
     * Add tag
     *
     * @param \Plugins\ContentBundle\Entity\Tag $tag
     * @return BlocGeneral
     */
    public function addTag(\Plugins\ContentBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;
    
        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Plugins\ContentBundle\Entity\Tag $tag
     */
    public function removeTag(\Plugins\ContentBundle\Entity\Tag $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Get tag
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return BlocGeneral
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Set user
     *
     * @param \BootStrap\UserBundle\Entity\User $user
     * @return BlocGeneral
     */
    public function setUser(\BootStrap\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BootStrap\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


}