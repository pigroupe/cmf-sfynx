<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   Sfynx
 * @package    Bundle
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-12-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sfynx\CmfBundle\DependencyInjection\Compiler\PiTwigEnvironmentPass;
use Sfynx\CmfBundle\DependencyInjection\Compiler\AddDependencyRoute;

/**
 * CMF managment Bundle.
 *
 * @category   Sfynx
 * @package    Bundle
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxCmfBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        // register extension
        $container->addCompilerPass(new PiTwigEnvironmentPass());
        // register all route pages.
        $container->addCompilerPass(new AddDependencyRoute());
    }
    
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
    }
    
    /**
     * Shutdowns the Bundle.
     */
    public function shutdown()
    {
    }    
}
