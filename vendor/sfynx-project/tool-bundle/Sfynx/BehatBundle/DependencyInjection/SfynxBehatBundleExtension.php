<?php

namespace Sfynx\BehatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SfynxBehatBundleExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        //
        $container->setParameter('behat.servers', $config['servers']);
        $container->setParameter('behat.locales', $config['locales']);
        $container->setParameter('behat.options', $config['options']);
    }
    
    public function getAlias()
    {
    	return 'sfynx_tool_behat';
    }     
}
