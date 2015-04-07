<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Service
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
namespace Sfynx\ToolBundle\Util;

use Sfynx\ToolBundle\Builder\PiConfigManagerBuilderInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\ToolBundle\Exception\ExtensionException;

/**
 * Configuration service of the CMF system
 *
 * @category   Tool
 * @package    Util
 * @subpackage Service
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
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
     * @param string $order    ['prepend', 'append']
     * @return void
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function setConfig($container, $type, array $options = null, $order = 'prepend')
    {
        if (!is_null($options) && (count($options) > 0)) {
            if (
                !isset($GLOBALS[ $container ]) 
                || 
                !isset($GLOBALS[ $container ][ $type ])
            ) {
                $GLOBALS[ $container ][ $type ] = array();
            }
            if ( isset($GLOBALS[ $container ][ $type ]) && (count($GLOBALS[ $container ][ $type ]) >= 1) ) {
                if ($order == 'append') {
                    $GLOBALS[ $container ][ $type ] = array_merge($GLOBALS[ $container ][ $type ], $options);
                } else {                    
                    $GLOBALS[ $container ][ $type ] = array_merge($options, $GLOBALS[ $container ][ $type ]);
                }
            } else {
                $GLOBALS[ $container ][ $type ] = $options;
            }
        }
    }        
}
