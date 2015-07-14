<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   Monolog
 * @package    Processor
 * @subpackage Cmf
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
namespace Sfynx\CmfBundle\Monolog\Processor;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use FOS\UserBundle\Model\UserInterface;
use Sfynx\CmfBundle\Entity\Page;
use Sfynx\CmfBundle\Entity\Block;
use Sfynx\CmfBundle\Entity\Widget;

/**
 * Custom login handler.
 * This allow you to execute code right after the user succefully logs in.
 * 
 * @category   Monolog
 * @package    Processor
 * @subpackage Cmf
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class IntrospectionCmfProcessor
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
        
        if (isset($record['context']['page'])) {
            $page = $record['context']['page'];
            if ($page instanceof Page ) {
                $record['context']['page'] = array(
                    "id"           => $page->getId(),
                    "page"         => $page->getRouteName(),
                    "translations" => $page->getTranslations()->count(),
                );
            }
        }
        
        if (isset($record['context']['block'])) {
            $block = $record['context']['block'];
            if ($block instanceof Block ) {
                $record['context']['block'] = array(
                    "id"       => $block->getId(),
                    "block"    => $block->getName(),
                    "widgets"  => $block->getWidgets()->count(),
                );
            }
        }        
        
        if (isset($record['context']['widget'])) {
            $widget = $record['context']['widget'];
            if ($widget instanceof Widget ) {
                $record['context']['widget'] = array(
                    "id"      => $widget->getId(),
                    "plugin"  => $widget->getPlugin(),
                    "action"  => $widget->getAction(),
                );
            }
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
