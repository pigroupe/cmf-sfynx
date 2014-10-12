<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage   Tool
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @subpackage   Tool
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxToolExtension extends Extension{

    public function load(array $config, ContainerBuilder $container)
    {
        // we load all services
        $loaderYaml  = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/service'));
        $loaderYaml->load('services.yml');
        $loaderYaml->load('services_util.yml');
        $loaderYaml->load('services_twig_extension.yml');
        // we load config
        $configuration = new Configuration();
        $config  = $this->processConfiguration($configuration, $config);        

        /**
         * Mail config parameter
         */
        if (isset($config['mail'])){
        	if (isset($config['mail']['overloading_mail'])) {
        		$container->setParameter('sfynx.tool.mail.overloading_mail', $config['mail']['overloading_mail']);
        	} else {
        		$container->setParameter('sfynx.tool.mail.overloading_mail', '');
        	}
        }
        
        /**
         * LayoutHead config parameter
         */
        $container->setParameter('js_files', array());
        $container->setParameter('css_files', array());        
    }
    
    public function getAlias()
    {
    	return 'sfynx_tool';
    }    

}
