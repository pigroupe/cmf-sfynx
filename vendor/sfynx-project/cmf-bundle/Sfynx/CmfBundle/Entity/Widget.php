<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   Cmf
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
namespace Sfynx\CmfBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Sfynx\CmfBundle\Twig\Extension\PiWidgetExtension;
use Sfynx\PositionBundle\Annotation as PI;

/**
 * Sfynx\CmfBundle\Entity\Widget
 *
 * @ORM\Table(name="pi_widget")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Repository\WidgetRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @category   Cmf
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Widget
{
    /**
     * @var bigint $id
     * 
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var \Sfynx\CmfBundle\Entity\Block $block
     * 
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Entity\Block", inversedBy="widgets", cascade={"persist"})
     * @ORM\JoinColumn(name="block_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $block;    
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $translations
     *
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Entity\TranslationWidget", mappedBy="widget", cascade={"all"})
     */
    protected $translations;    
    
    /**
     * @var string $plugin
     *
     * @ORM\Column(name="plugin", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $plugin;

    /**
     * @var string $action
     *
     * @ORM\Column(name="action", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $action; 

    /**
     * @var boolean $cacheable
     *
     * @ORM\Column(name="is_cacheable", type="boolean", nullable=true)
     */
    protected $cacheable = false;
    
    /**
     * @var boolean $public
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=true)
     */
    protected $public = false;
    
    /**
     * @var integer $lifetime
     *
     * @ORM\Column(name="lifetime", type="integer", nullable=true)
     */
    protected $lifetime = 3;    
    
    /**
     * @var boolean $public
     *
     * @ORM\Column(name="is_templating_cache", type="integer", nullable=true)
     */
    protected $cacheTemplating = 0; 

    /**
     * @var boolean $public
     *
     * @ORM\Column(name="is_ajax", type="integer", nullable=true)
     */
    protected $ajax = 0;    
    
    /**
     * @var boolean $sluggify
     *
     * @ORM\Column(name="is_sluggify", type="integer", nullable=true)
     */
    protected $sluggify = 0;    
    
    /**
     * @var string $configCssClass
     *
     * @ORM\Column(name="config_css_class", type="string", nullable=true)
     */
    protected $configCssClass;
    
    /**
     * @var boolean $secure
     *
     * @ORM\Column(name="is_secure", type="boolean", nullable=true)
     */
    protected $secure;
    
    /**
     * @var array
     * @ORM\Column(name="secure_roles", type="array", nullable=true)
     */
    protected $heritage;    
    
    /**
     * @var text $configXml
     *
     * @ORM\Column(name="config_xml", type="text", nullable=true)
     */
    protected $configXml;    

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $created_at;
    
    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updated_at;
    
    /**
     * @var datetime $archive_at
     *
     * @ORM\Column(name="archive_at", type="datetime", nullable=true)
     */
    protected $archive_at;    
        
    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;    

    /**
     * @var boolean $archived
     *
     * @ORM\Column(name="archived", type="boolean", nullable=false)
     */
    protected $archived = false;
        
    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"block","columnName":"block_id"})
     */
    protected $position;    
    

    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    
        $this->setEnabled(true);
        $this->setConfigXml(PiWidgetExtension::getDefaultConfigXml());
        $this->setLifetime('0');
    }

    /**
     *
     * This method is used when you want to convert to string an object of
     * this Entity
     * ex: $value = (string)$entity;
     *
     */
    public function __toString() {
        return (string) $this->id;
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
     * Set id
     *
     * @return integer
     */
    public function setId($id)
    {
    	$this->id = (int) $id;
    }    

    /**
     * Set plugin
     *
     * @param string $plugin
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Get plugin
     *
     * @return string 
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Set action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set configCssClass
     *
     * @param string $configCssClass
     */
    public function setConfigCssClass($configCssClass)
    {
        $this->configCssClass = $configCssClass;
    }

    /**
     * Get configCssClass
     *
     * @return string 
     */
    public function getConfigCssClass()
    {
        return $this->configCssClass;
    }

    /**
     * Set configXml
     *
     * @param text $configXml
     */
    public function setConfigXml($configXml)
    {
        $this->configXml = $configXml;
    }

    /**
     * Get configXml
     *
     * @return text 
     */
    public function getConfigXml()
    {
        return $this->configXml;
    }
    
    /**
     * Set cacheable
     *
     * @param boolean $cacheable
     */
    public function setCacheable($cacheable)
    {
        $this->cacheable = $cacheable;
    }
    
    /**
     * Get cacheable
     *
     * @return boolean
     */
    public function getCacheable()
    {
        return $this->cacheable;
    }
    
    /**
     * Set cacheTemplating
     *
     * @param boolean $cacheTemplating
     */
    public function setCacheTemplating($cacheTemplating)
    {
    	$this->cacheTemplating = $cacheTemplating;
    }
    
    /**
     * Get cacheTemplating
     *
     * @return boolean
     */
    public function getCacheTemplating()
    {
    	return $this->cacheTemplating;
    }   

    /**
     * Set ajax
     *
     * @param boolean $ajax
     */
    public function setAjax($ajax)
    {
    	$this->ajax = $ajax;
    }
    
    /**
     * Get ajax
     *
     * @return boolean
     */
    public function getAjax()
    {
    	return $this->ajax;
    }  

    /**
     * Set sluggify
     *
     * @param boolean $sluggify
     */
    public function setSluggify($sluggify)
    {
    	$this->sluggify = $sluggify;
    }
    
    /**
     * Get sluggify
     *
     * @return boolean
     */
    public function getSluggify()
    {
    	return $this->sluggify;
    }    
    
    /**
     * Set public
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }
    
    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
    
    /**
     * Set lifetime
     *
     * @param integer $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }
    
    /**
     * Get lifetime
     *
     * @return integer
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }
    

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
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

    /**
     * Set block
     *
     * @param \Sfynx\CmfBundle\Entity\Block $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
    }

    /**
     * Get block
     *
     * @return \Sfynx\CmfBundle\Entity\Block 
     */
    public function getBlock()
    {
        return $this->block;
    }
    
    /**
     * Set the collection of related translations
     *
     * @param \Doctrine\Common\Collections\ArrayCollection
     */
    public function setTranslations(\Doctrine\Common\Collections\ArrayCollection $translations)
    {
        $this->translations = $translations;
    }    

    /**
     * Add translations
     *
     * @param \Sfynx\CmfBundle\Entity\TranslationWidget
     */
    public function addTranslation(\Sfynx\CmfBundle\Entity\TranslationWidget $translation)
    {
        $this->translations->add($translation);
        $translation->setWidget($this);        
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }
    
    /**
     * Set secure
     *
     * @param boolean $secure
     */
    public function setSecure($secure)
    {
    	$this->secure = $secure;
    }
    
    /**
     * Get secure
     *
     * @return boolean
     */
    public function getSecure()
    {
    	return $this->secure;
    }
    
    /**
     * Set Role
     *
     * @param array $heritage
     */
    public function setHeritage( array $heritage)
    {
    	$this->heritage = array();
    
    	foreach ($heritage as $role) {
    		$this->addRoleInHeritage($role);
    	}
    }
    
    /**
     * Get heritage
     *
     * @return array
     */
    public function getHeritage()
    {
    	return $this->heritage;
    }
    
    /**
     * Adds a role heritage.
     *
     * @param string $role
     */
    public function addRoleInHeritage($role)
    {
    	$role = strtoupper($role);
    
    	if (!in_array($role, $this->heritage, true)) {
    		$this->heritage[] = $role;
    	}
    }    
    
    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }
    
    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }
    
    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    /**
     * Set archive_at
     *
     * @param datetime $archiveAt
     */
    public function setArchiveAt($archiveAt)
    {
        $this->archive_at = $archiveAt;
    }
    
    /**
     * Get archive_at
     *
     * @return datetime
     */
    public function getArchiveAt()
    {
        return $this->archive_at;
    }
    
    
    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
    
    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }    
    
    /**
     * Set archived
     *
     * @param boolean $enabled
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
    }
    
    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }    
}