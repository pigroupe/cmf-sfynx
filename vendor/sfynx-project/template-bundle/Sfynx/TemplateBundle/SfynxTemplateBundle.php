<?php
/**
 * This file is part of the <Template> project.
 * 
 * @subpackage   Sfynx
 * @package    Bundle
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TemplateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Template managment Bundle
 *
 * @subpackage   Sfynx
 * @package    Bundle
 * @author <etienne de Longeaux> <etienne.delongeaux@gmail.com>
 */
class SfynxTemplateBundle extends Bundle
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
     * 
     * @author <etienne de Longeaux> <etienne.delongeaux@gmail.com>
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
    
    /**
     * 
     * Boots the Bundle.
     * 
     * @author <etienne de Longeaux> <etienne.delongeaux@gmail.com>
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
