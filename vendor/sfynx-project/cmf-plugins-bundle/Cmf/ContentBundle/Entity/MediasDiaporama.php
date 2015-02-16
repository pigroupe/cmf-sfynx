<?php

namespace Cmf\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use BootStrap\TranslationBundle\Model\AbstractDefault;
use BootStrap\TranslationBundle\Annotation as PI;

/**
 * Cmf\ContentBundle\Entity\MediasDiaporama
 * @ORM\Table(name="cont_media_diaporama")
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\MediasDiaporamaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Cmf\ContentBundle\Entity\Translation\MediasDiaporamaTranslation")
 *
 * @category   plugins_Entities
 * @package    Entity
 * 
 */
class MediasDiaporama extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields    = array();

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Cmf\ContentBundle\Entity\Translation\MediasDiaporamaTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\Translation\MediasDiaporamaTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @var string $title
     * @ORM\Column(name="title", type="string", length=128, nullable=true)
     */
    private $title;    

    /**
     * @var string $descriptif
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    private $descriptif;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;
  
    /**
     * @ORM\ManyToOne(targetEntity="Cmf\ContentBundle\Entity\Diaporama", inversedBy="medias", cascade={"persist"})
     * @ORM\JoinColumn(name="diapo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $diaporama;    
    
    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"diaporama","columnName":"diapo_id"})
     */
    protected $position;   
    

    /**
     * Constructor
     */
    public function __construct()
    {
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
        return (string) " > ";
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
     * Set medias
     *
     * @param \PiApp\GedmoBundle\Entity\Media $medias
     * @return MediasDiaporama
     */
    public function setMedia(\PiApp\GedmoBundle\Entity\Media $media = null)
    {
        $this->media = $media;
        return $this;
    }
 
    /**
     * Get medias
     *
     * @return \PiApp\GedmoBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
    
    /**
     * Set diaporama
     *
     * @param \Cmf\ContentBundle\Entity\Diaporama
     */
    public function setDiaporama($diapo)
    {
    	$this->diaporama = $diapo;
    }
    
    /**
     * Get diaporama
     *
     * @return \Cmf\ContentBundle\Entity\Diaporama
     */
    public function getDiaporama()
    {
    	return $this->diaporama;
    }
    

    /**
     * Set $position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
        //return $this;
    }
    
    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    } 
}