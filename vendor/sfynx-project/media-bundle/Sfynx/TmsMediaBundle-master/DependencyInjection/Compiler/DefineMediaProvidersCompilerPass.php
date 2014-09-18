<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class DefineMediaProvidersCompilerPass implements CompilerPassInterface
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

        // StorageMapper
        $ruleServiceIds = array();
        $ruleServices = $container->findTaggedServiceIds('tms_media.mapper.rule');

        foreach ($ruleServices as $id => $attributes) {
            $ruleServiceIds[$attributes[0]['alias']] = $id;
        }

        $storageMappersConfig = $container->getParameter('tms_media.config.storage_mappers');
        foreach ($storageMappersConfig as $storageMapperId => $storageMapperConfig) {
            $storageMapperServiceId = sprintf(
                'tms_media.storage_mapper.%s',
                $storageMapperId
            );

            $storageMapperDefinition = new DefinitionDecorator('tms_media.storage_mapper');
            $storageMapperDefinition->setAbstract(false);
            $storageMapperDefinition->replaceArgument(0, new Reference($storageMapperConfig['storage_provider']));
            $storageMapperDefinition->replaceArgument(1, $storageMapperConfig['storage_provider']);

            // Injection of the rules in the provider.
            foreach ($storageMapperConfig['rules'] as $ruleAlias => $ruleArguments) {
                $ruleDefinition = new DefinitionDecorator($ruleServiceIds[$ruleAlias]);
                $ruleDefinition->setAbstract(false);
                $ruleDefinition->replaceArgument(0, $ruleArguments);

                $ruleServiceId = sprintf(
                    '%s.%s',
                    $ruleServiceIds[$ruleAlias],
                    $storageMapperId
                );

                $container->setDefinition($ruleServiceId, $ruleDefinition);

                $storageMapperDefinition->addMethodCall(
                    'addRule',
                    array(new Reference($ruleServiceId))
                );
            }

            $container->setDefinition($storageMapperServiceId, $storageMapperDefinition);

            $definition->addMethodCall(
                'addStorageMapper',
                array(new Reference($storageMapperServiceId))
            );
        }
    }
}
