<?php
/**
 * This file is part of the <SonataMedia> project.
 *
 * @category   SonataMedia
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
namespace Sfynx\MediaBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @category   SonataMedia
 * @package    DependencyInjection
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
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
