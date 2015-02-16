<?php
/**
 * This file is part of the <WSSE> project.
 *
 * @category   Sfynx
 * @package    Bunlde
 * @subpackage Ws-wsse
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2014 Air France
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sfynx\WsseBundle\Security\Factory\WsseFactory;

/**
 * Sfynx configuration and managment of the Webservice Bundle
 *
 * @category   Sfynx
 * @package    Bunlde
 * @subpackage Ws-wsse
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2014 Air France
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class SfynxWsseBundle extends Bundle 
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
    	parent::build($container);
        
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }
    
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
    }
    
    /**
     * Shutdowns the Bundle.
     */
    public function shutdown()
    {
    }
}