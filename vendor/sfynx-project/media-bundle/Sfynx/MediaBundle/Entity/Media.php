<?php
/**
 * This file is part of the <Media> project.
 *
 * @subpackage   Media
 * @package    Entity
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-07-31
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

/**
 * Sfynx\MediaBundle\Entity\Media
 *
 * @ORM\Table(name="sfynx_media")
 * @ORM\Entity(repositoryClass="Sfynx\MediaBundle\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Sfynx\MediaBundle\Entity\Translation\MediaTranslation")
 *
 * @subpackage   Media
 * @package    Entity
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Media extends AbstractDefault 
{
    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('title');
    
    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
    */
    protected $_translationClass = 'Sfynx\MediaBundle\Entity\Translation\MediaTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sfynx\MediaBundle\Entity\Translation\MediaTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @ORM\Column(name="status", type="string", nullable=false)
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
     * @var \BootStrap\MediaBundle\Entity\Media $image
     *
     * @ORM\ManyToOne(targetEntity="BootStrap\MediaBundle\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="media", referencedColumnName="id", nullable=true)
     */
    protected $file;
    
    /**
     * @var \BootStrap\MediaBundle\Entity\Media $image2
     *
     * @ORM\ManyToOne(targetEntity="BootStrap\MediaBundle\Entity\Media", cascade={"all"})
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
     */
    protected $copyright;    
    
    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"category","columnName":"category"})
     */
    protected $position;
    
    
    /**
     * Constructor
     */    
    public function __construct()
    {
        parent::__construct();
        
        $this->block = new \Doctrine\Common\Collections\ArrayCollection();
        $this->block2 = new \Doctrine\Common\Collections\ArrayCollection();
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
    		$content .=  '('. $cat->translate($locale)->getName() .')';
    	}
    	if ( ($this->getStatus() == 'image') && ($this->getImage() instanceof \BootStrap\MediaBundle\Entity\Media)) {
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
     * @param \BootStrap\MediaBundle\Entity\Media $image
     */
    public function setImage($image)
    {
        $this->image     = $image;
        return $this;
    }
    
    /**
     * Get image
     *
     * @return \BootStrap\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }    
    
    /**
     * Set image2
     *
     * @param \BootStrap\MediaBundle\Entity\Media $image2
     */
    public function setImage2($image2)
    {
    	$this->image2     = $image2;
    	return $this;
    }
    
    /**
     * Get image2
     *
     * @return \BootStrap\MediaBundle\Entity\Media
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

}