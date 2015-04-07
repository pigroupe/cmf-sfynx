<?php
/**
 * This file is part of the <CmfPluginsContent> project.
 *
 * @category   CmfPluginsContent
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
namespace Cmf\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sfynx\CoreBundle\Model\AbstractDefault;

/**
 * Cmf\ContentBundle\Entity\Tag
 *
 * @ORM\Table(name="gedmo_tag")
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Cmf\ContentBundle\Entity\Translation\TagTranslation")
 * 
 * @category   CmfPluginsContent
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Tag extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields  = array('title');

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Cmf\ContentBundle\Entity\Translation\TagTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\Translation\TagTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;      
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Cmf\ContentBundle\Entity\BlocGeneral", mappedBy="tag")
     */
    private $blocgeneral;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->blocgeneral = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->getTitle();
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
     * @return Tag
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
     * Add blocgeneral
     *
     * @param Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral
     * @return Tag
     */
    public function addBlocgeneral(\Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral)
    {
        $this->blocgeneral[] = $blocgeneral;
    
        return $this;
    }

    /**
     * Remove blocgeneral
     *
     * @param Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral
     */
    public function removeBlocgeneral(\Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral)
    {
        $this->blocgeneral->removeElement($blocgeneral);
    }

    /**
     * Get blocgeneral
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBlocgeneral()
    {
        return $this->blocgeneral;
    }

}