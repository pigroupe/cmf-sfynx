<?php
/**
 * This file is part of the <SonataMedia> project.
 *
 * @category   SonataMedia
 * @package    Bunlde
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
namespace Sfynx\MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sfynx\MediaBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use Sfynx\MediaBundle\DependencyInjection\Compiler\PiTwigEnvironmentPass;

/**
 * Sonata extend bundle
 *
 * @category   SonataMedia
 * @package    Bunlde
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class SfynxMediaBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataMediaBundle';
    }
    
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
        $container->addCompilerPass(new OverrideServiceCompilerPass());

        $container->setParameter('kernel.http_host', '');
        //$container->setParameter('sonata.media.provider.file.class.class', 'Sfynx\MediaBundle\Provider\FileProvider');
        //$container->setParameter('sonata.media.thumbnail.format', 'Sfynx\MediaBundle\Thumbnail\FormatThumbnail');
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
