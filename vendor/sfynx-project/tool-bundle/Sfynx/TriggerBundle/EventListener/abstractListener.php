<?php
/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage abstract
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
namespace Sfynx\TriggerBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * abstract listener manager.
 * This event is called after an entity is constructed by the EntityManager.
 *
 * @subpackage Core
 * @package    EventListener
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class abstractListener
{
     /**
     * @var ContainerInterface
     */
    protected $container;    

    /**
     * Constructor
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container  = $container;
    }
  
    /**
     * Return the token object.
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getToken()
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
    protected function getUserName()
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
    protected function getUserPermissions()
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
    protected function getUserRoles()
    {
        return $this->getToken()->getUser()->getRoles();
    }    
    
    /**
     * Sets the flash message.
     *
     * @param string $message
     * @param string $type
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setFlash($message, $type = "permission")
    {
           $this->getFlashBag()->add($type, $message);
    }

    /**
     * Gets the flash bag.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBag
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getFlashBag()
    {
        return $this->container->get('request')->getSession()->getFlashBag();
    }
        
    /**
     * Return if yes or no the user is anonymous token.
     *
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isAnonymousToken()
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
    protected function isUsernamePasswordToken()
    {
        if ($this->getToken() instanceof \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken) {
            return true;
        } else {
            return false;
        }
    }
}
