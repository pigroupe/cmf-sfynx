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

/**
 * Sfynx\CmfBundle\Entity\HistoricalStatus
 *
 * @ORM\Table(name="pi_page_historical_status")
 * @ORM\Entity(repositoryClass="Sfynx\CmfBundle\Repository\HistoricalStatusRepository")
 * @ORM\HasLifecycleCallbacks
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
class HistoricalStatus
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @var \Sfynx\CmfBundle\Entity\TranslationPage $order
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Entity\TranslationPage", inversedBy="historicalStatus", cascade={"all"})
     * @ORM\JoinColumn(name="pagetrans_id", referencedColumnName="id")
     */
    protected $pageTranslation;
 
 
    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    protected $status; 
 
    /**
     * @var text $comment
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;
 
    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $created_at;
    
    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled; 
    
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
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text 
     */
    public function getComment()
    {
        return $this->comment;
    }
    
    /**
     * Set pageTranslation
     *
     * @param \Sfynx\CmfBundle\Entity\TranslationPage
     */
    public function setPageTranslation(\Sfynx\CmfBundle\Entity\TranslationPage $pageTranslation)
    {
        $this->pageTranslation = $pageTranslation;
    }
    
    /**
     * Get pageTranslation
     *
     * @return \Sfynx\CmfBundle\Entity\TranslationPage
     */
    public function getPageTranslation()
    {
        return $this->pageTranslation;
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
}