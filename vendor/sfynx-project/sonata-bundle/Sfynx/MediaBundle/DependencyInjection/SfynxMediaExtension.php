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
    Symfony\Component\Config\FileLocator,
    Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

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
        
        /**
         * Crop config parameter
         */
        $this->registerDoctrineMapping($config);     

        $loaderXml = new Loader\XmlFileLoader($container, new FileLocator(realpath(__DIR__ . '/../Resources/config/service')));
        $loaderXml->load('security.xml');
        
        $loaderXmlForm = new Loader\XmlFileLoader($container, new FileLocator(realpath(__DIR__ . '/../Resources/config')));
        $loaderXmlForm->load('form.xml');
    }
    
   /**
     * @param array $config
     *
     * @return void
     */
    public function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();
        
        if (class_exists('Sfynx\MediaBundle\Entity\Translation\MediathequeTranslation')) {
            $collector->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'translations',
                'targetEntity'  => 'Sfynx\MediaBundle\Entity\Translation\MediathequeTranslation',
                'cascade'       => array(
                    'persist',
                    'remove',
                ),
                'mappedBy'      => 'object',
                'orderBy'       => array(
                    'locale'  => 'ASC',
                ),
            ));
            $collector->addAssociation('Sfynx\MediaBundle\Entity\Translation\MediathequeTranslation', 'mapManyToOne', array(
                'fieldName'     => 'object',
                'targetEntity'  => 'Sfynx\MediaBundle\Entity\Mediatheque',
                'cascade'       => array(),
                'inversedBy'    => 'translations',
                'joinColumns'   =>  array(
                    array(
                        'name'  => 'object_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'CASCADE'
                    ),
                ),
            ));
        }   
        
        if (class_exists('PiApp\GedmoBundle\Entity\Category')) {
            $collector->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapManyToOne', array(
                'fieldName'     => 'category',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Category',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => NULL,
                'inversedBy'    => 'items_media',
                'joinColumns'   =>  array(
                    array(
                        'name'  => 'category',
                        'referencedColumnName' => 'id',
                        'nullable' => true
                    ),
                ),
                'orphanRemoval' => false,
            ));
        }
        
        if (class_exists('Sfynx\MediaBundle\Entity\Media')) {
            $collector->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapManyToOne', array(
                'fieldName'     => 'image',
                'targetEntity'  => 'Sfynx\MediaBundle\Entity\Media',
                'cascade'       => array(
                    'all',
                ),
                'joinColumns'   =>  array(
                    array(
                        'name'  => 'media',
                        'referencedColumnName' => 'id',
                        'nullable' => true
                    ),
                ),
                'orphanRemoval' => false,
            ));
            $collector->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapManyToOne', array(
                'fieldName'     => 'image2',
                'targetEntity'  => 'Sfynx\MediaBundle\Entity\Media',
                'cascade'       => array(
                    'all',
                ),
                'joinColumns'   =>  array(
                    array(
                        'name'  => 'media2',
                        'referencedColumnName' => 'id',
                        'nullable' => true
                    ),
                ),
                'orphanRemoval' => false,
            ));            
        } 
    }
    
    public function getAlias()
    {
    	return 'sfynx_media';
    }      
}
