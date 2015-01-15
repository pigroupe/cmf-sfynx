<?php
/**
 * This file is part of the <Tool> project.
 * 
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2013-11-14
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
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
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
