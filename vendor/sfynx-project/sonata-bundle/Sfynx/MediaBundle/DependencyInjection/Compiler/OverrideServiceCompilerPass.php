<?php
/**
 * This file is part of the <Media> project.
 *
 * @category Media
 * @package  OverrideService
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since    2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds tagged twig.extension services to the pi_app_admin twig service
 *
 * @category Media
 * @package  OverrideService
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sonata.media.form.type.media');
        $definition->setClass('Sfynx\MediaBundle\Form\Type\MediaType');
        
        $definition = $container->getDefinition('sonata.media.thumbnail.format');
        $definition->setClass('Sfynx\MediaBundle\Thumbnail\FormatThumbnail');
        
        $definition = $container->getDefinition('sonata.media.provider.image');
        $definition->setClass('Sfynx\MediaBundle\Provider\ImageProvider');
        
        $definition = $container->getDefinition('sonata.media.provider.file');
        $definition->setClass('Sfynx\MediaBundle\Provider\FileProvider');
    }
}
