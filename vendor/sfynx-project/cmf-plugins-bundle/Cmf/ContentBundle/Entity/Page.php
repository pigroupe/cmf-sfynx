<?php

namespace Cmf\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Sfynx\CoreBundle\Model\AbstractDefault;

/**
 * Cmf\ContentBundle\Entity\Page
 * @ORM\Table(name="cont_page")
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Cmf\ContentBundle\Entity\Translation\PageTranslation")
 *
 * @category   plugins_Entities
 * @package    Entity
 * 
 */
class Page extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields    = array('content');

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Cmf\ContentBundle\Entity\Translation\PageTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\Translation\PageTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @var string $url
     * 
     * @ORM\Column(name="url", type="string", length=128, nullable=true)
     */
    protected $url;
 
    /**
     * @var string $metaKeywords
     *
     * @ORM\Column(name="meta_keywords", type="string", nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string $metaDescription
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;
    
    /**
     * @var string $content
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    /**
     * @var integer $sidebar
     *
     * @ORM\Column(name="sidebar", type="boolean", nullable=true)
     */
    private $sidebar;
    
    /**
     * @var integer $blocgeneral
     *
     * @ORM\OneToOne(targetEntity="Cmf\ContentBundle\Entity\BlocGeneral" , cascade={"all"}, inversedBy="page");
     * @ORM\JoinColumn(name="blocgeneral_id", referencedColumnName="id", nullable=true)
     * @Assert\Valid
     */
    protected $blocgeneral;  
    
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
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set url
     * 
     * @param string $url
     */    
    public function setUrl($url)
    {
    	$this->url = $this->slugify($url);
        return $this;
    }
    
    /**
     * Set url
     *
     * @ORM\PrePersist
     * @param string $url
     */
    public function setUrlPrepersist()
    {
    	if (!empty($this->url)) {
    		$this->url = $this->slugify($this->url);
    	} else {
    		$this->url = $this->slugify($this->blocgeneral->getTitle());
    	}
    	return $this;
    }    
    
    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
    	return $this->url;
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
 
    /**
     * Set sidebar
     *
     * @param boolean $sidebar
     */    
    public function setSidebar($sidebar)
    {
        $this->sidebar = $sidebar;
        return $this;
    }

    /**
     * Get sidebar
     *
     * @return integer
     */    
    public function getSidebar()
    {
        return $this->sidebar;
    }
    
    /**
     * Set blocgeneral
     *
     * @param \Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral
     * @return Page
     */
    public function setBlocgeneral(\Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral = null)
    {
        $this->blocgeneral = $blocgeneral;
    
        return $this;
    }

    /**
     * Get blocgeneral
     *
     * @return \Cmf\ContentBundle\Entity\BlocGeneral 
     */
    public function getBlocgeneral()
    {
        return $this->blocgeneral;
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