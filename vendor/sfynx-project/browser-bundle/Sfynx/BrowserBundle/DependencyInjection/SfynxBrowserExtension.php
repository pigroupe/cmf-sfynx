<?php
/**
 * This file is part of the <Browser> project.
 *
 * @subpackage   Browser
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\BrowserBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @subpackage   Browser
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxBrowserExtension extends Extension{

    public function load(array $config, ContainerBuilder $container)
    {
        // we load all services
        $loaderYaml  = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/service'));
        $loaderYaml->load('services.yml');
        // we load config
        $configuration = new Configuration();
        $config  = $this->processConfiguration($configuration, $config);        

        /**
         * Browscap config parameter
         */
        if( $config['browscap']['cache_dir'] === null ) {
            $config['browscap']['cache_dir'] = $container->getParameter('kernel.cache_dir');
        }
        foreach ($config['browscap'] as $k => $v) {
            $container->setParameter('sfynx.browser.browscap.' . $k, $v);
        }
    }
    
    public function getAlias()
    {
    	return 'sfynx_browser';
    }    

}
