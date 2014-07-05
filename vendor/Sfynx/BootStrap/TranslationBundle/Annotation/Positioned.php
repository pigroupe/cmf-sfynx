<?php

/**
 * This file is part of the <Translation> project.
 *
 * @category   BootStrap_annotation
 * @package    Position
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-06-02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BootStrap\TranslationBundle\Annotation;

/**
 * The positioned class handles the @Position annotation.
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @Annotation
 */
class Positioned {
    public $routes = true;
    public $SortableOrders = array();
    // some parameters will be added
}