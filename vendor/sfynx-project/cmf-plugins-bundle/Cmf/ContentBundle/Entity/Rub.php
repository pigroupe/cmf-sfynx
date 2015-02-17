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
namespace Cmf\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Sfynx\CoreBundle\Model\AbstractDefault;

/**
 * Cmf\ContentBundle\Entity\Rub
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="cont_rub")
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\RubRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Cmf\ContentBundle\Entity\Translation\RubTranslation")
 *
 * @UniqueEntity("title")
 * 
 * @category   plugins_Entities
 * @package    Entity
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Rub extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields    = array('slug', 'title', 'descriptif','referencement', 'meta_keywords', 'meta_description');

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Cmf\ContentBundle\Entity\Translation\RubTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\Translation\RubTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @var string
     * 
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message = "erreur.title.notblank")
     */
    protected $title;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="titleref", type="string", length=128, nullable=true)
     */
    protected $titleref;


    /**
     * @Gedmo\Translatable
     * 
     * @ORM\Column(name="slug", length=255, nullable=true)
     */
    private $slug;
  
    /**
     * @var string $descriptif
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    private $descriptif;
  
    /**
     * @var string $referencement
     * @Gedmo\Translatable
     * @ORM\Column(name="referencement", type="text", nullable=true)
     */
    private $referencement;
  
    /**
     * @var string $section
     * 
     * @ORM\Column(name="section", type="text", nullable=true)
     */
    private $section;
    
    /**
     * @var integer $blocgeneral
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\BlocGeneral", mappedBy="mainrub")
     * @ORM\JoinColumn(name="blocgeneral_id", referencedColumnName="id", nullable=true)
     */
    protected $blocgeneral;  
    
    /**
     * @var integer $blocgeneral2
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\BlocGeneral", mappedBy="subrub")
     * @ORM\JoinColumn(name="blocgeneral2_id", referencedColumnName="id", nullable=true)
     */
    protected $blocgeneral2;  

    /**
     * @var integer $media
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Entity\Mediatheque" , inversedBy="cont_rub");
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=314, nullable=true)
     */
    protected $url; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->childrens = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this->setEnabled(true);            
    }
    
    public function __toString()
    {
        return (string) strip_tags($this->getTitle());
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
     * @return Rub
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->slug  = $this->slugify($title);
    
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
     * Set titleref
     *
     * @param string $titleref
     * @return Rub
     */
    public function setTitleref($titleref)
    {
        $this->titleref= $titleref;

        return $this;
    }

    /**
     * Get titleref
     *
     * @return string
     */
    public function getTitleref()
    {
        return $this->titleref;
    }

    public function slugify($text)
    {
        // delete all tags
        $text = strip_tags($text);
        
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\/\d]+#u', '-', $text);
        
        // delete all accent
        $translit = array('Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A','Ã'=>'A','Å'=>'A','Ç'=>'C','É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E','Í'=>'I','Ï'=>'I','Î'=>'I','Ì'=>'I','Ñ'=>'N','Ó'=>'O','Ò'=>'O','Ô'=>'O','Ö'=>'O','Õ'=>'O','Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','á'=>'a','à'=>'a','â'=>'a','ä'=>'a','ã'=>'a','å'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o','õ'=>'o','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y');
        $text       = strtr($text, $translit);
        
        // trim
        $text = trim($text, '-');
    
        // transliterate
        if (function_exists('iconv'))
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
        // lowercase
        $text = strtolower($text);
    
        if (empty($text))
            return '';
        else
            return $text;
    }    
    
    public function getSlug()
    {
        return $this->slug;
    }  
 
    /**
     * Set descriptif
     *
     * @param string $descriptif
     * @return Rub
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
     * Set section
     *
     * @param string $section
     * @return Rub
     */
    public function setSection($section)
    {
        $this->section = $section;
    
        return $this;
    }

    /**
     * Get section
     *
     * @return string 
     */
    public function getSection()
    {
        return $this->section;
    }      
 
    /**
     * Set referencement
     *
     * @param string $referencement
     * @return Rub
     */
    public function setReferencement($referencement)
    {
        $this->referencement = $referencement;
    
        return $this;
    }

    /**
     * Get referencement
     *
     * @return string 
     */
    public function getReferencement()
    {
        return $this->referencement;
    }
    
    /**
     * Set media
     *
     * @param \Sfynx\MediaBundle\Entity\Mediatheque $media
     */
    public function setMedia($media)
    {
        $this->media = $media;        
        return $this;
    }
    
    /**
     * Get media
     *
     * @return \Sfynx\MediaBundle\Entity\Mediatheque
     */
    public function getMedia()
    {
        return $this->media;
    } 
    
    /**
     * Set blocgeneral2
     *
     * @param Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral
     * @return Rub
     */
    public function setBlocgeneral2(\Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral2 = null)
    {
        $this->blocgeneral2 = $blocgeneral2;
    
        return $this;
    }

    /**
     * Get blocgeneral2
     *
     * @return Cmf\ContentBundle\Entity\BlocGeneral 
     */
    public function getBlocgeneral2()
    {
        return $this->blocgeneral2;
    }
    
    /**
     * Set blocgeneral
     *
     * @param Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral
     * @return Rub
     */
    public function setBlocgeneral(\Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral = null)
    {
        $this->blocgeneral = $blocgeneral;
    
        return $this;
    }

    /**
     * Get blocgeneral
     *
     * @return Cmf\ContentBundle\Entity\BlocGeneral 
     */
    public function getBlocgeneral()
    {
        return $this->blocgeneral;
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
     * Set $url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    /**
     * Get $url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }  
    
    /*******************************************************************************************************
     *
     * Tree definition
     *
     *******************************************************************************************************/
    
    
    /**
     * @var \Cmf\ContentBundle\Entity\Rub $parent
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="\Cmf\ContentBundle\Entity\Rub", inversedBy="childrens", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $parent;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $childrens
     *
     * @ORM\OneToMany(targetEntity="\Cmf\ContentBundle\Entity\Rub", mappedBy="parent", cascade={"all"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $childrens;
    
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", nullable=true)
     */
    private $lft;
    
    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", nullable=true)
     */
    private $rgt;
    
    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    private $lvl;
    
    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;
    
    /**
     * @var array parents_tree
     */
    protected $parents_tree = null;
    
    /**
     * Get childrens
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildrens()
    {
        return $this->childrens;
    }
    
    /**
     * Set parent
     *
     * @param \Cmf\ContentBundle\Entity\Rub    $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
    
    /**
     * Get parent
     *
     * @return \Cmf\ContentBundle\Entity\Rub
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Set the parent tree
     *
     * @param array $parents
     */
    public function setTreeParents(array $parents)
    {
        $this->parents_tree = $parents;
    }
    
    /**
     * get the tree of the page, build it from the parent if the tree does not exist
     *
     * @return array\Sonata\PageBundle\Model\PageInterface
     */
    public function getTreeParents()
    {
        if (!$this->parents_tree) {
    
            $page = $this;
            $parents = array();
    
            while ($page->getParent()) {
                $page = $page->getParent();
                $parents[] = $page;
            }
    
            $this->setTreeParents(array_reverse($parents));
        }
    
        return $this->parents_tree;
    }
    
    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }
    
    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * Get lft
     *
     * @return integer
     */
    public function getLeft()
    {
        return $this->lft;
    }
    
    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRight()
    {
        return $this->rgt;
    }
    
    /**
     * Set lft
     *
     * @param integer $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }
    
    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }
    
    /**
     * Set lvl
     *
     * @param integer $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }
    
    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }
    
    /**
     * Set rgt
     *
     * @param integer $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }
    
    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }      

}