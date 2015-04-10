<?php
/**
 * This file is part of the <Template> project.
 *
 * @category   Template
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
namespace Sfynx\TemplateBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @category   Template
 * @package    DependencyInjection
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
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
            if (isset($config['form'])) {
                foreach ($config['form'] as $key => $value) {
                    if (is_array($value)) {
                        foreach ($config['form'][$key] as $subkey => $subvalue) {
                            $container->setParameter(
                                    'sfynx.template.form.extension.'.$key.'.'.$subkey,
                                    $subvalue
                            );
                        }
                    } else {
                        $container->setParameter(
                            'sfynx.template.form.extension.'.$key,
                            $value
                        );
                    }
                }
            }                
        }
    }
    
    public function getAlias()
    {
    	return 'sfynx_template';
    }
        
}