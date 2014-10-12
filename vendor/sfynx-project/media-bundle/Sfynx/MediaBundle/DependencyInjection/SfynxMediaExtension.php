<?php
/**
 * This file is part of the <Media> project.
 *
 * @subpackage   Media
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MediaBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @subpackage   Media
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxMediaExtension extends Extension{

    public function load(array $config, ContainerBuilder $container)
    {
        // we load all services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('services_twig_extension.yml');
        // we load config
        $configuration = new Configuration();
        $config  = $this->processConfiguration($configuration, $config);
        
        /**
         * Admin config parameter
         */
        if (isset($config['crop'])){
        	$container->setParameter('sfynx.media.crop', $config['crop']);
        	if (isset($config['crop']['formats'])) {
        		$container->setParameter('sfynx.media.crop.formats', $config['crop']['formats']);
        	}
        }        
     
    }
    
    public function getAlias()
    {
    	return 'sfynx_media';
    }    

}
