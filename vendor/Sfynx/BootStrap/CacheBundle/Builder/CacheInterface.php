<?php
/**
 * This file is part of the <Cache> project.
 *
 * @category   BootStrap
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BootStrap\CacheBundle\Builder;

use BootStrap\CacheBundle\Builder\CacheClientInterface;

/**
 * CacheInterface 
 * 
 * @category   BootStrap
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface CacheInterface
{
    public function __construct( CacheClientInterface $client = null );
    public function get( $key );
    public function set( $key, $value, $ttl );
    public function isSafe();
    public function clear($key);
}
