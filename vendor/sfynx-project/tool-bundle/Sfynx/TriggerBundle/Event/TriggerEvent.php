<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Trigger
 * @package    Event
 * @subpackage Object
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
namespace Sfynx\TriggerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Common\EventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Response event of connection user.
 *
 * @category   Trigger
 * @package    Event
 * @subpackage Object
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 */
class TriggerEvent extends Event
{
    /**
     * @var EventArgs $eventArgs
     */
    private $eventArgs;
    
    /**
     * @var Object $entities
     */
    private $entities;
    
    /**
     * @var array $options
     */
    private $options;  
    
     /**
     * @var ContainerInterface
     */
    private $container;       
   
    
    public function __construct($eventArgs, ContainerInterface $container)
    {
        $this->eventArgs  = $eventArgs;
        $this->container  = $container;
    }
    
    /**
     * @return eventArgs
     */
    public function getContainer()
    {
    	return $this->container;
    }
    
    /**
     * @return eventArgs
     */
    public function geteventArgs()
    {
    	return $this->eventArgs;
    }
    
    /**
     * @return locale
     */
    public function getOptions()
    {
    	return $this->options;
    }
    
    /**
     * @return  void
     */
    public function setOptions($option, $status)
    {
    	$this->options[$status][] = $option;
    }   
    
    /**
     * @return redirect
     */
    public function getEntities()
    {
    	return $this->entities;
    }
    
    /**
     * @return  void
     */
    public function setEntities($entity, $status = "persist")
    {
    	$this->entities[$status][] = $entity;
    }   
    
    /**
     * @return object entity
     */
    public function getEntity()
    {
    	return $this->eventArgs->getEntity();
    }   
    
    /**
     * @return object Manager
     */
    public function getEntityManager()
    {
    	return $this->eventArgs->getEntityManager();
    }      
    
    /**
     * @return object UnitOfWork Manager
     */
    public function getUnitOfWork()
    {
    	return $this->getEntityManager()->getUnitOfWork();
    }  
    
   /**
     * Return the token object.
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getToken()
    {
        return  $this->container->get('security.context')->getToken();
    }

    /**
     * Return the connected user name.
     *
     * @return string User name
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserName()
    {
        return $this->getToken()->getUser()->getUsername();
    }    
    
    /**
     * Return the user permissions.
     *
     * @return array User permissions
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserPermissions()
    {
        return $this->getToken()->getUser()->getPermissions();
    }  

    /**
     * Return the user roles.
     *
     * @return array User roles
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserRoles()
    {
        return $this->getToken()->getUser()->getRoles();
    }    
    
    /**
     * Return if yes or no the user is anonymous token.
     *
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isAnonymousToken()
    {
        if (
            ($this->getToken() instanceof \Symfony\Component\Security\Core\Authentication\Token\AnonymousToken)
            ||
            ($this->getToken() === null)
        ) {
            return true;
        } else {
            return false;
        }
    }    
    
    /**
     * Return if yes or no the user is UsernamePassword token.
     *
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isUsernamePasswordToken()
    {
        if ($this->getToken() instanceof \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken) {
            return true;
        } else {
            return false;
        }
    }    
}
