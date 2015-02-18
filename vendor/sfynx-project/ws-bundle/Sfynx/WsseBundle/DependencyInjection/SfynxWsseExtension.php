<?php
/**
 * This file is part of the <Ws-se> project.
 *
 * @category   Ws-se
 * @package    DependencyInjection
 * @subpackage Extension
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
namespace Sfynx\WsseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 * 
 * @category   Ws-se
 * @package    DependencyInjection
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class SfynxWsseExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        /**
         * Cache config parameter
         */
        if (isset($config['security'])){
            if (isset($config['security']['cache_dir'])) {
                $container->setParameter('sfynx.wsse.security.cache_dir', $config['security']['cache_dir']);
            }
            if (isset($config['security']['nonce_lifetime'])) {
                $container->setParameter('sfynx.wsse.security.nonce_lifetime', $config['security']['nonce_lifetime']);
            }      
        }          
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAlias() {
        return 'sfynx_wsse';
    }    
}
