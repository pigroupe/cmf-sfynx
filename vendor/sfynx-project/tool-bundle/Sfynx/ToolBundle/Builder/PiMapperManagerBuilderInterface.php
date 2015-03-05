<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Builder
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
namespace Sfynx\ToolBundle\Builder;

use Sfynx\ToolBundle\Util\PiMapperManager;

/**
 * Mapper builder interface.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Builder
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
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
