<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util\Soap;

use \Sfynx\ToolBundle\Util\Soap\PiSoapModelInterface;
use \Sfynx\ToolBundle\Util\Soap\PiSoapManagerInterface;

/**
 * Construct a soap proxy class
 *
 * <code>
 *     $soapProxy = $this->get('nosbelidees.common.soap_proxy');
 * </code>
 * 
 * @subpackage Tool
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiSoapProxy
{
    /**
     * @var PiSoapManagerInterface $soapmanager
     */
    private $soapmanager;

    /**
     * Constructor.
     *
     * @param PiSoapManagerInterface $soapmanager
     * 
     * @return void
     */        
    public function __construct(PiSoapManagerInterface $soapmanager)
    {
        $this->soapmanager = $soapmanager;
    }
    
    /**
     * Send soap method
     * 
     * @param  PiSoapModelInterface $object
     * 
     * @return object the soap call result
     */       
    public function send(PiSoapModelInterface $object, $option)
    {
        $object->setSoapManager($this->soapmanager);
        $object->setOption($option);
        $object->create();
        
        return $object->call();
    } 
}
