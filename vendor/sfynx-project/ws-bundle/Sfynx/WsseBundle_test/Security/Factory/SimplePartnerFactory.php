<?php

namespace Sfynx\WsseBundle\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class SimplePartnerFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.nbi_simplepartner.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('nbi_simplepartner.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.nbi_simplepartner.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('nbi_simplepartner.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'nbi_simplepartner';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}
