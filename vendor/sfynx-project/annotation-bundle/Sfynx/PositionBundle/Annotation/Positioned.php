<?php

/**
 * This file is part of the <Position> project.
 *
 * @category   Position
 * @package    Annotation
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-06-02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\PositionBundle\Annotation;

/**
 * The positioned class handles the @Position annotation.
 * 
 * <code>
 *     @PI\Positioned(SortableOrders = {"type":"relationship","field":"page","columnName":"page_id"})
 * </code>
 * 
 * @category   Position
 * @package    Annotation
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @Annotation
 */
class Positioned {
    public $routes = true;
    public $SortableOrders = array();
    // some parameters will be added
}