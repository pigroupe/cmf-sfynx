<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Bundle
 * @package    DependencyInjection
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OrApp\OrGedmoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds tagged twig.extension services to the pi_app_admin twig service
 *
 * @category   Bundle
 * @package    DependencyInjection
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('piapp_gedmobundle_mediatype_file');
        $definition->setClass('OrApp\OrGedmoBundle\Form\MediaType');
        $definition->setArguments(array(
        		new \Symfony\Component\DependencyInjection\Reference('service_container'),
        		new \Symfony\Component\DependencyInjection\Reference('doctrine.orm.entity_manager'),
        		'file',
        		'media_collection',
        		'all'
        ));
         
        $definition = $container->getDefinition('piapp_gedmobundle_mediatype_image');
        $definition->setClass('OrApp\OrGedmoBundle\Form\MediaType');
        $definition->setArguments(array(
        		new \Symfony\Component\DependencyInjection\Reference('service_container'),
        		new \Symfony\Component\DependencyInjection\Reference('doctrine.orm.entity_manager'),
        		'image',
        		'media_collection',
        		'all'
        ));
         
        $definition = $container->getDefinition('piapp_gedmobundle_mediatype_youtube');
        $definition->setClass('OrApp\OrGedmoBundle\Form\MediaType');
        $definition->setArguments(array(
        		new \Symfony\Component\DependencyInjection\Reference('service_container'),
        		new \Symfony\Component\DependencyInjection\Reference('doctrine.orm.entity_manager'),
        		'youtube',
        		'media_collection',
        		'all'
        ));
        
        $definition = $container->getDefinition('piapp_gedmobundle_mediatype_dailymotion');
        $definition->setClass('OrApp\OrGedmoBundle\Form\MediaType');
        $definition->setArguments(array(
        		new \Symfony\Component\DependencyInjection\Reference('service_container'),
        		new \Symfony\Component\DependencyInjection\Reference('doctrine.orm.entity_manager'),
        		'dailymotion',
        		'media_collection',
        		'all'
        ));        
    }
}