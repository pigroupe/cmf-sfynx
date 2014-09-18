<?php
/**
 * This file is part of the <Template> project.
 *
 * @category   Template
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TemplateBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @category   Template
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxTemplateExtension extends Extension{

    public function load(array $config, ContainerBuilder $container)
    {
        // we load all services
        $loaderYaml = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/service'));
        $loaderYaml->load("services_form_extension.yml");
        // we load config
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);
        
        /**
         * Form config parameter
         */
        if (isset($config['form'])){
        	if (isset($config['form']['show_legend'])) {
        		$container->setParameter('sfynx.template.form.extension.show_legend', $config['form']['show_legend']);
        	}
        	if (isset($config['form']['show_child_legend'])) {
        		$container->setParameter('sfynx.template.form.extension.show_child_legend',$config['form']['show_child_legend']);
        	}
        	if (isset($config['form']['error_type'])) {
        		$container->setParameter('sfynx.template.form.extension.error_type',$config['form']['error_type']);
        	}
        }
    }
    
    public function getAlias()
    {
    	return 'sfynx_template';
    }
        
}