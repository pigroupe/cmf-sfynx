<?php
/**
 * This file is part of the <CmfPluginsGedmo> project.
 *
 * @category   CmfPluginsGedmo
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
namespace PiApp\GedmoBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sfynx\CoreBundle\Model\AbstractDefault;
use Sfynx\PositionBundle\Annotation as PI;

/**
 * PiApp\GedmoBundle\Entity\Content
 *
 * @ORM\Table(name="gedmo_content")
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\ContentRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="PiApp\GedmoBundle\Entity\Translation\ContentTranslation")
 *
 * @category   CmfPluginsGedmo
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Content extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields    = array('content', 'descriptif');

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'PiApp\GedmoBundle\Entity\Translation\ContentTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Entity\Translation\ContentTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @var string
     *
     * @ORM\Column(name="page_cssclass", type="string", length=128, nullable=true)
     */
    protected $pagecssclass;    
    
    /**
     * @var \PiApp\GedmoBundle\Entity\Category $category
     * 
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Entity\Category", inversedBy="items_content")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    protected $category;   
    
    /**
     * @var text $descriptif
     * 
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif", type="string", length=128, nullable=true)
     */
    protected $descriptif;   

    /**
     * @var string
     * 
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;
    
    /**
     * @var \Sfynx\CmfBundle\Entity\Page $pageurl
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Entity\Page")
     * @ORM\JoinColumn(name="page_intro_id", referencedColumnName="id", nullable=true)
     */
    protected $pageurl;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=314, nullable=true)
     */
    protected $url;    
    
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
        return (string) $this->getCategory() . " > " .$this->getDescriptif ();
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
     * Set pagecssclass
     *
     * @param string $className
     */
    public function setPagecssclass($className)
    {
        $this->pagecssclass = $className;
    }
    
    /**
     * Get pagecssclass
     *
     * @return string
     */
    public function getPagecssclass()
    {
        return $this->pagecssclass;
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
     * Set descriptif
     *
     * @param text $descriptif
     */
    public function setDescriptif ($descriptif)
    {
        $this->descriptif = $descriptif;
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
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }   

    /**
     * Set content
     *
     * @param string $text
     */
    public function setContent($text)
    {
        $this->content = $text;
    }
    
    /**
     * Set page url
     *
     * @param \Sfynx\CmfBundle\Entity\Page
     */
    public function setPageurl($pageurl)
    {
        $this->pageurl = $pageurl;
        return $this;
    }
    
    /**
     * Get page url
     *
     * @return \Sfynx\CmfBundle\Entity\Page
     */
    public function getPageurl()
    {
        return $this->pageurl;
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

}