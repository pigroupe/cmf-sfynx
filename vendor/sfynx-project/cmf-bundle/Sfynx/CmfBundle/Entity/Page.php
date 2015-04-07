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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\Index;


/**
 * Sfynx\CmfBundle\Entity\Page
 *
 * @ORM\Table(name="pi_page", indexes={@ORM\Index(name="route_name_idx", columns={"route_name"})})
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Repository\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("route_name")
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
class Page
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
     * @var \Sfynx\AuthBundle\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\AuthBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Entity\TranslationPage", mappedBy="page", cascade={"all"})
     * @Assert\Valid
     */
    protected $translations;  
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Sfynx\CmfBundle\Entity\Block", mappedBy="page", cascade={"all"})
     * @Assert\NotBlank()
     */
    protected $blocks; 

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Entity\Menu", mappedBy="page", cascade={"persist"})
     */
    protected $menus;    

    /**
     * @var \Sfynx\CmfBundle\Entity\Rubrique $rubrique
     * 
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Entity\Rubrique")
     * @ORM\JoinColumn(name="rubrique_id", referencedColumnName="id", nullable=true)
     */
    protected $rubrique;

    /**
     * @var \Sfynx\AuthBundle\Entity\Layout $layout
     * 
     * @ORM\ManyToOne(targetEntity="Sfynx\AuthBundle\Entity\Layout", cascade={"persist"})
     * @ORM\JoinColumn(name="layout_id", referencedColumnName="id", nullable=true)
     */
    protected $layout;    
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $keywords
     *
     * @ORM\ManyToMany(targetEntity="Sfynx\CmfBundle\Entity\KeyWord")
     * @ORM\JoinTable(name="pi_keyword_page",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="keyword_id", referencedColumnName="id")}
     *      )
     */
    protected $keywords;    
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $page_css
     *
     * @ORM\ManyToMany(targetEntity="Sfynx\CmfBundle\Entity\Page")
     * @ORM\JoinTable(name="pi_page_css",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pagecss_id", referencedColumnName="id")}
     *      )
     */
    protected $page_css; 

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $page_js
     *
     * @ORM\ManyToMany(targetEntity="Sfynx\CmfBundle\Entity\Page")
     * @ORM\JoinTable(name="pi_page_js",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pagejs_id", referencedColumnName="id")}
     *      )
     */
    protected $page_js;    

    /**
     * @var boolean $cacheable
     * 
     * @ORM\Column(name="is_cacheable", type="boolean", nullable=true)
     */
    protected $cacheable;
    
    /**
     * @var boolean $public
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=true)
     */
    protected $public;    
    
    /**
     * @var integer $lifetime
     * 
     * @ORM\Column(name="lifetime", type="integer", nullable=true)
     */
    protected $lifetime = 0;    
    
    /**
     * @var string $route_name
     * 
     * @ORM\Column(name="route_name", type="string", nullable=true, unique=true)
     * @Assert\Length(min = 3, minMessage = "Le route name doit avoir au moins {{ limit }} caractères")
     */
    protected $route_name;
    
    /**
     * @var string $url
     * 
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    protected $url;    
        
    /**
     * @var string $meta_content_type
     * 
     * @ORM\Column(name="meta_content_type", type="string", nullable=false)
     * @Assert\NotBlank(message = "erreur.metacontenttype.notblank")
     */
    protected $meta_content_type;   
    
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

    public function __construct()
    {
        $this->translations    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blocks        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->menus        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->keywords        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->page_css        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->page_js        = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this->setEnabled(true);
    }
    
    public function __toString()
    {
        return (string) $this->route_name;
    }    
    
//     /**
//      * @ORM\PrePersist
//      */
//     public function setCreatedValue()
//     {
//         $this->setCreatedAt(new \DateTime());
//         $this->setUpdatedAt(new \DateTime());
//     }
    
//     /**
//      * @ORM\PreUpdate
//      */
//     public function setUpdatedValue()
//     {
//         $this->setUpdatedAt(new \DateTime());
//     }    
    
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
     * Set route_name
     *
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->route_name = $routeName;
    }

    /**
     * Get route_name
     *
     * @return string 
     */
    public function getRouteName()
    {
        return $this->route_name;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $this->route_name == 'home_page' ? '' : $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->route_name == 'home_page' ? '' : $this->url;
    }

    /**
     * Set meta_content_type
     *
     * @param string $metaContentType
     */
    public function setMetaContentType($metaContentType)
    {
        $this->meta_content_type = $metaContentType;
    }

    /**
     * Get meta_content_type
     *
     * @return string 
     */
    public function getMetaContentType()
    {
        return $this->meta_content_type;
    }

    /**
     * Set user
     *
     * @param \Sfynx\AuthBundle\Entity\User
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return \Sfynx\AuthBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the collection of related translations
     *
     * @param \Doctrine\Common\Collections\ArrayCollection    $translations
     */
    public function setTranslations(\Doctrine\Common\Collections\ArrayCollection $translations)
    {
        $this->translations = $translations;
    }
        
    /**
     * Add a translation to the collection of related translations
     *
     * @param \Sfynx\CmfBundle\Entity\TranslationPage    $translation
     */
    public function addTranslation(\Sfynx\CmfBundle\Entity\TranslationPage $translation)
    {
        if (!$this->translations->contains($translation))
              $this->translations->add($translation);
        
        $translation->setPage($this);
    }
    
    /**
     * Remove a translation from the collection of related translations
     *
     * @param \Sfynx\CmfBundle\Entity\TranslationPage $translation
     */
    public function removeTranslation(\Sfynx\CmfBundle\Entity\TranslationPage $translation)
    {
        //if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        //}
    }    

    /**
     *  Get the collection of related translations
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }
    
    /**
     *  Get the translation according to the language
     *
     * @param string $locale
     * 
     * @return \Sfynx\CmfBundle\Entity\TranslationPage
     */
    public function getTranslationByLocale($locale)
    {
        foreach($this->translations as $key => $trans){
            if ($trans->getLangCode()->getId() == $locale) {
                return $trans;
            }
        }
        return null;
    }    

    /**
     * Add blocks
     *
     * @param \Sfynx\CmfBundle\Entity\Block $block
     */
    public function addBlock(\Sfynx\CmfBundle\Entity\Block $block)
    {
        $this->blocks->add($block);
        $block->setPage($this);
    }

    /**
     * Get blocks
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBlocks()
    {
    	// we order by position value.
    	$iterator = $this->blocks->getIterator();
    	$iterator->uasort(function ($first, $second) {
            if ($first === $second) {
                return 0;
            }

            return (int) $first->getPosition() < (int) $second->getPosition() ? -1 : 1;
    	});
    	$this->blocks = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));
    	
        return $this->blocks;
    }

    /**
     * Set rubrique
     *
     * @param \Sfynx\CmfBundle\Entity\Rubrique $rubrique
     */
    public function setRubrique($rubrique)
    {
        $this->rubrique = $rubrique;
    }

    /**
     * Get rubrique
     *
     * @return \Sfynx\CmfBundle\Entity\Rubrique 
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }

    /**
     * Set layout
     *
     * @param \Sfynx\AuthBundle\Entity\Layout $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Get layout
     *
     * @return \Sfynx\AuthBundle\Entity\Layout 
     */
    public function getLayout()
    {
        return $this->layout;
    }
    
    /**
     * Add Css Page
     *
     * @param \Sfynx\CmfBundle\Entity\Page $Page
     */
    public function addPageCss(\Sfynx\CmfBundle\Entity\Page $Page)
    {
        $this->page_css[] = $Page;
    }
    
    /**
     * Set Css Page
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $Page
     */
    public function setPageCss($Pages)
    {
        $this->page_css = $Pages;
    }
    
    /**
     * Get Css Pages
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPageCss()
    {
        return $this->page_css;
    }    
    
    /**
     * Add Js Page
     *    
     * @param \Sfynx\CmfBundle\Entity\Page $Page
     */
    public function addPageJs(\Sfynx\CmfBundle\Entity\Page $Page)
    {
        $this->page_js[] = $Page;
    }
    
    /**
     * Set Js Page
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $Pages
     */    
    public function setPageJs($Pages)
    {
        $this->page_js = $Pages;
    }    

    /**
     * Get Js Pages
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPageJs()
    {
        return $this->page_js;
    }   

    /**
     * Add keyWord
     *
     * @param \Sfynx\CmfBundle\Entity\KeyWord $keywords
     */
    public function addKeyWord(\Sfynx\CmfBundle\Entity\KeyWord $keywords)
    {
        $this->keywords[] = $keywords;
    }
    
    /**
     * Set keywords
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $keyword
     */    
    public function setKeywords($keyword)
    {
        $this->keywords = $keyword;
    }    

    /**
     * Get keywords
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getKeywords()
    {
        return $this->keywords;
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
    
    /**
     * Get menus
     *
     * @return string
     */
    public function getMenus()
    {
        return $this->menus;
    }    

}