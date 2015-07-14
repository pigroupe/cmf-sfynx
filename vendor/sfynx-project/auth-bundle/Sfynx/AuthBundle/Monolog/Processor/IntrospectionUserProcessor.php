<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Monolog
 * @package    Processor
 * @subpackage User
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Monolog\Processor;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use FOS\UserBundle\Model\UserInterface;

/**
 * Custom login handler.
 * This allow you to execute code right after the user succefully logs in.
 * 
 * @category   Monolog
 * @package    Processor
 * @subpackage User
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class IntrospectionUserProcessor
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function processRecord(array $record)
    {
        if($this->isUsernamePasswordToken()) {   
            $record['extra']['user'] = array(
                'username' => $this->getUser()->getUsername(),
                'email'    => $this->getUser()->getEmail(),
            );
        }

        return $record;
    }
    
    /**
     * Return if yes or no the user is anonymous token.
     *
     * @return boolean
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isAnonymousToken()
    {
        if (
            ($this->getToken() instanceof AnonymousToken)
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
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isUsernamePasswordToken()
    {
        if ($this->getToken() instanceof UsernamePasswordToken) {
            return true;
        } else {
            return false;
        }
    }        
    
    /**
     * Return the connected user entity.
     *
     * @return UserInterface
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUser()
    {
        return $this->getToken()->getUser();
    }    
    
    /**
     * Return the token object.
     *
     * @return UsernamePasswordToken
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getToken()
    {
        return  $this->container->get('security.token_storage')->getToken();
    }        
}
