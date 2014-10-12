<?php
/**
 * This file is part of the <web service> project.
 *
 * @subpackage   Sfynx
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @subpackage   Sfynx
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxWsExtension extends Extension {

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        
        /**
         * Auth config parameter
         */
        if (isset($config['auth'])) {
            $container->setParameter('ws.auth', $config['auth']);
        }

        /**
         * SSO config parameter
         */
        if (isset($config['sso'])) {
            foreach ($config['sso'] as $k => $v) {
                $container->setParameter('ws.sso.' . $k, $v);
            }
        }        
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias() {
        return 'sfynx_ws';
    }
}