<?php
/**
 * This file is part of the <SonataMedia> project.
 *
 * @category   SonataMedia
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MediaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sfynx\CoreBundle\Model\AbstractDefault;
use Sfynx\PositionBundle\Annotation as PI;
use Sfynx\MediaBundle\Entity\AddTrait;
use Sfynx\MediaBundle\Entity\Media;


/**
 * Sfynx\MediaBundle\Entity\Mediatheque
 *
 * @ORM\Table(name="gedmo_media")
 * @ORM\Entity(repositoryClass="Sfynx\MediaBundle\Repository\MediathequeRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Sfynx\MediaBundle\Entity\Translation\MediathequeTranslation")
 *
 * @category   SonataMedia
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Mediatheque extends AbstractDefault 
{
    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('title', 'descriptif');
    
    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
    */
    protected $_translationClass = 'Sfynx\MediaBundle\Entity\Translation\MediathequeTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sfynx\MediaBundle\Entity\Translation\MediathequeTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;
        
    /**
     * @var bigint
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var \PiApp\GedmoBundle\Entity\Category $category
     * 
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Entity\Category", inversedBy="items_media")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    protected $category;    
    
    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=25, nullable=true)
     * @Assert\NotBlank(message = "erreur.status.notblank")
     */
    protected $status;    

    /**
     * @var string $title
     * 
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255, nullable = true)
     */
    protected $title;      
    
    /**
     * @var text $descriptif
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    protected $descriptif;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=314, nullable=true)
     */
    protected $url;    
    
    /**
     * @var \Sfynx\MediaBundle\Entity\Media $image
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="media", referencedColumnName="id", nullable=true)
     */
    protected $image;
    
    /**
     * @var \Sfynx\MediaBundle\Entity\Media $image2
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="media2", referencedColumnName="id", nullable=true)
     */
    protected $image2;    
    
    /**
     * @var boolean $mediadelete
     *
     * @ORM\Column(name="mediadelete", type="boolean", nullable=true)
     */
    protected $mediadelete; 
    
    /**
     * @var string $copyright
     * 
     * @ORM\Column(name="copyright", type="string", length=255, nullable=true)
     */
    protected $copyright;    
    
    use AddTrait;
    
    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"category","columnName":"category"})
     */
    protected $position;
    
    /**
     * Get magic method
     */      
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * Set magic method
     */     
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }    
    
    /**
     * Constructor
     */    
    public function __construct()
    {
        parent::__construct();
    }    
    
    /**
     *
     * This method is used when you want to convert to string an object of
     * this Entity
     * ex: $value = (string)$entity;
     *
     */    
    public function __toString()
    {
    	if (isset($_GET['_locale']) && !empty($_GET['_locale'])) {
            $locale = $_GET['_locale'];
    	} else {
            $locale = "fr_FR";
    	}
    	$content = $this->getId();
    	$title = $this->translate($locale)->getTitle();
    	$cat = $this->getCategory();
    	if ($title) {
            $content .=  " - " .$title;
    	}
    	if (!is_null($cat)) {
            $content .=  ' ('. $cat->getName() .')';
    	}
    	if (($this->getStatus() == 'image') 
                && ($this->getImage() instanceof Media)
        ) {
            $content .= "<img width='100px' src=\"{{ media_url('".$this->getImage()->getId()."', 'small', true, '".$this->getUpdatedAt()->format('Y-m-d H:i:s')."', 'gedmo_media_') }}\" alt='Photo'/>";
    	}
    	
        return (string) $content;
    }   
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
    }  
    
    /**
     * Get id
     *
     * @return bigint
     */    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set category
     *
     * @param \PiApp\GedmoBundle\Entity\Category $category
     */
    public function setCategory($category)
    {
        
        $this->category = $category;
        return $this;
    }
    
    /**
     * Get category
     *
     * @return \PiApp\GedmoBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }     
    
    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }    
    
    /**
     * Set title
     *
     * @param string $title
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
     * @param text $descriptif
     */
    public function setDescriptif ($descriptif)
    {
    	$this->descriptif = $descriptif;
    	return $this;
    }
    
    /**
     * Get descriptif
     *
     * @return text
     */
    public function getDescriptif ()
    {
    	return $this->descriptif;
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

    /**
     * Set image
     *
     * @param \Sfynx\MediaBundle\Entity\Media $image
     */
    public function setImage($image)
    {
        $this->image     = $image;
        return $this;
    }
    
    /**
     * Get image
     *
     * @return \Sfynx\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }    
    
    /**
     * Set image2
     *
     * @param \Sfynx\MediaBundle\Entity\Media $image2
     */
    public function setImage2($image2)
    {
    	$this->image2     = $image2;
    	return $this;
    }
    
    /**
     * Get image2
     *
     * @return \Sfynx\MediaBundle\Entity\Media
     */
    public function getImage2()
    {
    	return $this->image2;
    }    
    
    /**
     * Set mediadelete
     *
     * @param boolean $mediadelete
     */
    public function setMediadelete($mediadelete)
    {
        $this->mediadelete = $mediadelete;
        return $this;
    }
    
    /**
     * Get mediadelete
     *
     * @return boolean
     */
    public function getMediadelete()
    {
        return $this->mediadelete;
    }  
    
    /**
     * {@inheritdoc}
     */
    public function setCopyright($copyright)
    {
    	$this->copyright = $copyright;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCopyright()
    {
    	return $this->copyright;
    }    

//    
//    /**
//     * Get image
//     *
//     * @return \Sfynx\MediaBundle\Entity\Media
//     */
//    public function getContact1()
//    {
//        return $this->contact1;
//    }
//    public function getContact2()
//    {
//        return $this->contact2;
//    }    
//    public function getMenu()
//    {
//        return $this->menu;
//    }
//    public function getSlider()
//    {
//        return $this->slider;
//    }
//    public function getBlock()
//    {
//        return $this->block;
//    }
//    public function getBlock2()
//    {
//        return $this->block2;
//    }    
//    public function getOrganigram()
//    {
//        return $this->organigram;
//    }       
//    public function getEntitycategory()
//    {
//        return $this->entitycategory;
//    }
//    
//    /**
//     * Set entitycategory
//     *
//     * @param \PiApp\GedmoBundle\Entity\Category $category
//     */
//    public function setEntitycategory(\PiApp\GedmoBundle\Entity\Category $category)
//    {
//        $this->entitycategory = $category;
//    }   
//  
//    /**
//     * Set contact1
//     *
//     * @param \PiApp\GedmoBundle\Entity\Contact $contact1
//     */
//    public function setContact1(\PiApp\GedmoBundle\Entity\Contact $contact1)
//    {
//        $this->contact1 = $contact1;
//    }
//
//    /**
//     * Set contact2
//     *
//     * @param \PiApp\GedmoBundle\Entity\Contact $contact2
//     */
//    public function setContact2(\PiApp\GedmoBundle\Entity\Contact $contact2)
//    {
//        $this->contact2 = $contact2;
//    }
//
//    /**
//     * Set menu
//     *
//     * @param \PiApp\GedmoBundle\Entity\Menu $menu
//     */
//    public function setMenu(\PiApp\GedmoBundle\Entity\Menu $menu)
//    {
//        $this->menu = $menu;
//    }
//
//    /**
//     * Set slider
//     *
//     * @param \PiApp\GedmoBundle\Entity\Slider $slider
//     */
//    public function setSlider(\PiApp\GedmoBundle\Entity\Slider $slider)
//    {
//        $this->slider = $slider;
//    }
//
//    /**
//     * Set block
//     *
//     * @param \PiApp\GedmoBundle\Entity\Block $block
//     */
//    public function setBlock(\PiApp\GedmoBundle\Entity\Block $block)
//    {
//        $this->block = $block;
//    }
//    
//    /**
//     * Set block2
//     *
//     * @param \PiApp\GedmoBundle\Entity\Block $block
//     */
//    public function setBlock2(\PiApp\GedmoBundle\Entity\Block $block)
//    {
//        $this->block2 = $block;
//    }    
//
//    /**
//     * Set organigram
//     *
//     * @param \PiApp\GedmoBundle\Entity\Organigram $organigram
//     */
//    public function setOrganigram(\PiApp\GedmoBundle\Entity\Organigram $organigram)
//    {
//        $this->organigram = $organigram;
//    }
}