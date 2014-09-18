<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class DefineMediaTransformersCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('tms_media.manager.media')) {
            return;
        }

        $definition = $container->getDefinition('tms_media.manager.media');

        // MetadataExtractor
        $taggedServices = $container->findTaggedServiceIds('tms_media.transformer');

        foreach ($taggedServices as $id => $tagAttributes) {
            $definition->addMethodCall(
                'addMediaTransformer',
                array(new Reference($id), $id)
            );
        }
    }
}
