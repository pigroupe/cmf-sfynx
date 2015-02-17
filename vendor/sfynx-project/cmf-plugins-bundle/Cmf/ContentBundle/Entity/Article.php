<?php

namespace Cmf\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Sfynx\CoreBundle\Model\AbstractDefault;

/**
 * Cmf\ContentBundle\Entity\Article
 * @ORM\Table(name="cont_article")
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Cmf\ContentBundle\Entity\Translation\ArticleTranslation")
 *
 * @category   plugins_Entities
 * @package    Entity
 * 
 */
class Article extends AbstractDefault 
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
    protected $_translationClass = 'Cmf\ContentBundle\Entity\Translation\ArticleTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\Translation\ArticleTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255, nullable = true)
     * @Assert\NotBlank()
     */
    protected $type;

    /**
     * @var boolean popin
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="popin", type="boolean", nullable=true)
     */
    protected $popin;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255, nullable = true)
     */
    protected $url;
    
    /**
     * @var string $alias
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable = true)
     */
    protected $alias;    

    /**
     * @var string $content
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    /**
     * @var integer $blocgeneral
     *
     * @ORM\OneToOne(targetEntity="Cmf\ContentBundle\Entity\BlocGeneral" , cascade={"all"}, inversedBy="article");
     * @ORM\JoinColumn(name="blocgeneral_id", referencedColumnName="id", nullable=true)
     * @Assert\Valid
     */
    protected $blocgeneral;  
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
        
    public function __toString()
    {
        return substr((string) $this->getContent(), 0, 50);
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
     * Set type
     *
     * @param string $type
     * @return this
     */
    public function setType($type)
    {
    	$this->type = $type;
    	return $this;
    }
    
    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
    	return $this->type;
    }    

    /**
     * Set url
     *
     * @param string $url
     * @return this
     */
    public function setUrl($url)
    {
    	$this->url = $url;
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
    
    /**
     * Set alias
     *
     * @param string $alias
     * @return this
     */
    public function setAlias($alias)
    {
    	$this->alias = $alias;
    	return $this;
    }
    
    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
    	return $this->alias;
    }

    /**
     * Set popin
     *
     * @param string $popin
     * @return this
     */
    public function setPopin($popin)
    {
    	$this->popin = $popin;
    	return $this;
    }
    
    /**
     * Get popin
     *
     * @return string
     */
    public function getPopin()
    {
    	return $this->popin;
    }
        
    /**
     * Set content
     *
     * @param string $content
     * @return Article
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
     * Set blocgeneral
     *
     * @param \Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral
     * @return Article
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

}