<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Builder
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Builder;

use Sfynx\ToolBundle\Util\PiMapperManager;

/**
 * PiMapperManagerBuilderInterface interface.
 *
 * @subpackage Tool
 * @package    Builder
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiMapperManagerBuilderInterface
{
    /**
     * Return the if the xml tag name is supported by the mapper
     * On true the MapperCollection will call the map() method
     *
     * @return boolean
     */
    public function supports($tagName);

    /**
     * Map the xml to the object.
     * The mapper should return the completed object. It may ask the MapperCollection to
     * handle the unknown parts.
     *
     * @param  string          $xml
     * @param  PiMapperManager $collection
     * @return mixed
     */
    public function map($xml, PiMapperManager $collection);
}
