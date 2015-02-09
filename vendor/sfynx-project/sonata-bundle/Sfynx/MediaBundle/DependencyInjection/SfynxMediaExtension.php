<?php
/**
 * This file is part of the <Media> project.
 *
 * @category   BootStrap
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
 * @category   BootStrap
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxMediaExtension extends Extension{

    public function load(array $config, ContainerBuilder $container)
    {
        $loaderYaml = new Loader\YamlFileLoader($container, new FileLocator(realpath(__DIR__ . '/../Resources/config/service')));
        $loaderYaml->load('services.yml');
        $loaderYaml->load('services_twig_extension.yml');
        // we load config
        $configuration = new Configuration();
        $config  = $this->processConfiguration($configuration, $config);
        
        /**
         * Crop config parameter
         */
        if (isset($config['crop'])){
            $container->setParameter('sfynx.media.crop', $config['crop']);
            if (isset($config['crop']['formats'])) {
                $container->setParameter('sfynx.media.crop.formats', $config['crop']['formats']);
            }
        }    

        $loaderXml = new Loader\XmlFileLoader($container, new FileLocator(realpath(__DIR__ . '/../Resources/config/service')));
        $loaderXml->load('security.xml');
        
        $loaderXmlForm = new Loader\XmlFileLoader($container, new FileLocator(realpath(__DIR__ . '/../Resources/config')));
        $loaderXmlForm->load('form.xml');
    }
    
    public function getAlias()
    {
    	return 'sfynx_media';
    }      
}
