<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Sfynx
 * @package    Bunlde
 * @subpackage Tool
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sfynx\ToolBundle\DependencyInjection\Compiler\PiTwigEnvironmentPass;
use Sfynx\ToolBundle\DependencyInjection\Compiler\MapperCollectionPass;

/**
 * Sfynx configuration and managment of the Tool Bundle
 *
 * @category   Sfynx
 * @package    Bunlde
 * @subpackage Tool
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class SfynxToolBundle extends Bundle
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
        $container->addCompilerPass(new MapperCollectionPass());
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
