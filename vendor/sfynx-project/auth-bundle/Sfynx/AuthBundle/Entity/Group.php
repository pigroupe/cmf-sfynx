<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
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
namespace Sfynx\AuthBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Sfynx\AuthBundle\Repository\PermissionRepository;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_group")
 * 
 * @category   Auth
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
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
      * @var boolean $enabled
      *
      * @ORM\Column(name="enabled", type="boolean", nullable=true)
      */
     protected $enabled;
     
     /**
      * @var array
      * @ORM\Column(type="array")
      */
     protected $permissions = array('VIEW', 'EDIT', 'CREATE', 'DELETE');    

     public function __construct($name, $roles = array())
     {
         parent::__construct($name, $roles);
         
         $this->setCreatedAt(new \DateTime());
         $this->setUpdatedAt(new \DateTime());
     }
     
    /**
     * 
     * This method is used when you want to convert to string an object of
     * this Entity
     * ex: $value = (string)$entity;
     * 
     */    
    public function __toString() {
        return (string) $this->name;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedValue()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedValue()
    {
        $this->setUpdatedAt(new \DateTime());
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
     * Set permissions
     *
     * @param array $permissions
     */
    public function setPermissions(array $permissions)
    {
    	$this->permissions = array();
    	foreach ($permissions as $permission) {
    		$this->addPermission($permission);
    	}
    }
    
    /**
     * Get permissions
     *
     * @return array
     */
    public function getPermissions()
    {
    	$permissions = $this->permissions;
    	// we need to make sure to have at least one role
    	$permissions[] = PermissionRepository::ShowDefaultPermission();
    
    	return array_unique($permissions);
    }
    
    /**
     * Adds a permission to the user.
     *
     * @param string $permission
     */
    public function addPermission($permission)
    {
    	$permission = strtoupper($permission);
    	if (!in_array($permission, $this->permissions, true)) {
    		$this->permissions[] = $permission;
    	}
    }
    
    /**
     * Remove a permission to the user.
     *
     * @param string $permission
     */
    public function removePermission($permission)
    {
    	$permission = strtoupper($permission);
    	if (in_array($permission, $this->permissions, true)) {
    		$key = array_search($permission, $this->permissions);
    		unset($this->permissions[$key]);
    	}
    }    
}
