<?php

namespace Sfynx\WsseBundle;

use Sfynx\WsseBundle\Security\Factory\SimplePartnerFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SfynxWsseBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new SimplePartnerFactory());
    }
}
