<?php
/**
 * This file is part of the <Tool> project.
 * 
 * @category   Tool
 * @package    Util
 * @subpackage Service
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
namespace Sfynx\ToolBundle\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\ToolBundle\Builder\PiMapperManagerBuilderInterface;

/**
 * Mapper manager tool
 * 
 * @category   Tool
 * @package    Util
 * @subpackage Service
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class PiMapperManager
{
    private $mappers = array();
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct()    {}

    /**
     * Return a JS file in the container in links.
     *
     * @param PiMapperManagerBuilderInterface $mapper Mapper interface
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function add(PiMapperManagerBuilderInterface $mapper)
    {
        $this->mappers[] = $mapper;
    }

    /**
     * Return a JS file in the container in links.
     *
     * @param string $xml
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function map($xml)
    {
        $reader = new \XMLReader();
        $reading = $reader->xml($xml);
        $reader->read();
        foreach ($this->mappers as $mapper) {
            if ($mapper->supports($reader->name)) {
                return $mapper->map($xml, $this);
            }
        }

        throw new \Exception('No registered mapper for ' . $reader->name);
    }
}
