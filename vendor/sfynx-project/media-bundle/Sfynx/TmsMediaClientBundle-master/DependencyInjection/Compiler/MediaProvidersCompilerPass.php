<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaClientBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class MediaProvidersCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter(
            'tms_media_client.config.storage_providers',
            $container->findTaggedServiceIds('tms_media_client.storage_provider')
        );

        if ($container->hasDefinition('tms_media_client.storage_provider_handler')) {
            $definition = $container->getDefinition(
                'tms_media_client.storage_provider_handler'
            );

            $taggedServices = $container->findTaggedServiceIds(
                'tms_media_client.storage_provider'
            );

            foreach ($taggedServices as $id => $tagAttributes) {
                $providerDefinition = $container->getDefinition($id);
                $providerDefinition->addMethodCall('setName', array($id));

                $definition->addMethodCall(
                    'addStorageProvider',
                    array(new Reference($id), $id)
                );
            }
        }
    }
}
