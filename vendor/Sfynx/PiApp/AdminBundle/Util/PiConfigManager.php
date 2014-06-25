<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Utils
 * @package    Util
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Util;

use PiApp\AdminBundle\Builder\PiConfigManagerBuilderInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use PiApp\AdminBundle\Exception\ExtensionException;

/**
 * Configuration service of the CMF system
 *
 * @category   Admin_Utils
 * @package    Util
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiConfigManager implements PiConfigManagerBuilderInterface 
{    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * Invoked to modify the controller that should be executed.
     *
     * @param FilterControllerEvent $event The event
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function onKernelRequest($event){
    	return;
    }    
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
        
     /**
     * 
     * @param string $container
     * @param string $type
     * @param array $options
     * @return void
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function setConfig($container, $type, array $options)
    {
        if (
            !isset($GLOBALS[ $container ]) 
            || 
            !isset($GLOBALS[ $container ][ $type ])
        ) {
            $GLOBALS[ $container ][ $type ] = array();
        }
        if (is_null($options) || (count($options) == 0)) {
            throw ExtensionException::ConfigWidgetUnDefined( $container , $type);
        } else {
            $GLOBALS[ $container ][ $type ] = array_unique(array_merge($options, $GLOBALS[ $container ][ $type ]));
        }
    }    
    
}