<?php
/**
 * This file is part of the <Behat> project.
 *
 * @subpackage Tool
 * @package    Bundle
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\BehatBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Sfynx configuration and managment of the Behat Bundle
 *
 * @subpackage Tool
 * @package    Bundle
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxBehatBundle extends Bundle
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
