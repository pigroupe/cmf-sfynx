<?php

namespace Plugins\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use BootStrap\TranslationBundle\Model\AbstractDefault;


/**
 * Plugins\ContentBundle\Entity\Diaporama
 * @ORM\Table(name="cont_diaporama")
 * @ORM\Entity(repositoryClass="Plugins\ContentBundle\Repository\DiaporamaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Plugins\ContentBundle\Entity\Translation\DiaporamaTranslation")
 *
 * @category   plugins_Entities
 * @package    Entity
 * 
 */
class Diaporama extends AbstractDefault 
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
    protected $_translationClass = 'Plugins\ContentBundle\Entity\Translation\DiaporamaTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Plugins\ContentBundle\Entity\Translation\DiaporamaTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;    

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer $blocgeneral
     *
     * @ORM\OneToOne(targetEntity="Plugins\ContentBundle\Entity\BlocGeneral" , cascade={"all"}, inversedBy="diaporama");
     * @ORM\JoinColumn(name="blocgeneral_id", referencedColumnName="id", nullable=true)
     * @Assert\Valid
     */
    protected $blocgeneral;  

    /**
     * @ORM\OneToMany(targetEntity="Plugins\ContentBundle\Entity\MediasDiaporama", mappedBy="diaporama", cascade={"all"})
     * @Assert\Valid
     */
    private $medias;
    
    /**
     * @var integer $old_id
     * 
     * @ORM\Column(name="old_id", type="string", length=255, nullable=true)
     */
    private $old_id;    
    
    /**
     * @var string $old_url
     * 
     * @ORM\Column(name="old_url", type="text", nullable=true)
     */
    private $old_url; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->id;
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
     * Set blocgeneral
     *
     * @param \Plugins\ContentBundle\Entity\BlocGeneral $blocgeneral
     * @return Diaporama
     */
    public function setBlocgeneral($blocgeneral)
    {
        $this->blocgeneral = $blocgeneral;
    
        return $this;
    }

    /**
     * Get blocgeneral
     *
     * @return \Plugins\ContentBundle\Entity\BlocGeneral 
     */
    public function getBlocgeneral()
    {
        return $this->blocgeneral;
    }
    
    /**
     * Get medias
     *
     * @return \Plugins\ContentBundle\Entity\MediasDiaporama
     */
    public function getPositionMedias()
    {
        return $this->medias;
    }
    
    /**
     * Get medias
     *
     * @return Doctrine\Common\Collections\ArrayCollection 
     */
    public function getMedias()
    {
    	//$criteria = Criteria::create()->orderBy(array("created_at" => Criteria::ASC));
    	// we order by position value.
    	$iterator = $this->medias->getIterator();    	
    	$iterator->uasort(function ($first, $second) {
    		if ($first === $second) {
    			return 0;
    		}
    	
    		return (int) $first->getPosition() < (int) $second->getPosition() ? -1 : 1;
    	});    	
    	$this->medias = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));
    	
    	
	    return $this->medias;
    }    
    

    public function setMedias( $medias)
    {
/*        foreach($medias as $p){
            $p->addDiaporama($this);
        }
*/
        $this->medias = $medias;        
    } 
    
    
    public function addMedias($medias)
    {
        if (!$this->medias->contains($medias)) {
              $this->medias->add($medias);
        }        
        $medias->setDiaporama($this);
        
        return $this;
    }
    
    /**
     * Remove medias
     *
     * @param \Plugins\ContentBundle\Entity\MediasDiaporama $medias
     */
    public function removeMedias(\Plugins\ContentBundle\Entity\MediasDiaporama $media)
    {
        $this->medias->removeElement($media);
    }    
    
    /**
     * Set old_url
     *
     * @param integer $old_url
     * @return Test
     */
    public function setOldUrl($old_url)
    {
        $this->old_url = $old_url;
    
        return $this;
    }

    /**
     * Get old_url
     *
     * @return integer 
     */
    public function getOldUrl()
    {
        return $this->old_url;
    } 

    /**
     * Set old_id
     *
     * @param integer $old_id
     * @return Test
     */
    public function setOldId($old_id)
    {
        $this->old_id = $old_id;
    
        return $this;
    }

    /**
     * Get old_id
     *
     * @return integer 
     */
    public function getOldId()
    {
        return $this->old_id;
    }        
}