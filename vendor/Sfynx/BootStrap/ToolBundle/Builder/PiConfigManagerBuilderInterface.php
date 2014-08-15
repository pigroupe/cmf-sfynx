<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   BootStrap
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BootStrap\ToolBundle\Builder;

/**
 * PiArrayManagerBuilderInterface interface.
 *
 * @category   BootStrap
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiConfigManagerBuilderInterface
{
    public function setConfig($container, $type, array $options);
}