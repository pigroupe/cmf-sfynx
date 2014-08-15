<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Builder;

/**
 * DoctrineRouteInterface interface.
 *
 * @category   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface DoctrineRouteInterface
{
    public function addAllRoutePageCollections();
    public function parseRoutePages();
    public function getAllRouteValues();
    public function getAllRouteNames();
    public function getRoute($route);
    public function addRoute($route, $id, array $locales, array $defaults = array(), array $requirements = array());
}