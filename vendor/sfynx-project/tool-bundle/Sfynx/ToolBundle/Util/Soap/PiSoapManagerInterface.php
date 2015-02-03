<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-02-02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util\Soap;

/**
 * Soap Manager Interface
 *
 * @subpackage Tool
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiSoapManagerInterface
{
    /**
     * Set wsdl
     *
     * @param string $wsdl URI to the WSDL
     * 
     * @return PiSoapManager
     */
    public function setWsdl($wsdl);
    
    /**
     * Set soap options
     *
     * @param array $options
     * 
     * @return PiSoapManager
     */
    public function setOptions(array $options);
    
    /**
     * Set soap headers
     *
     * @param array $headers
     * 
     * @return PiSoapManager
     */    
    public function setHeaders(array $headers);  
    
    /**
     * Set soap cookie
     *
     * @param string $name
     * @param string $value
     * 
     * @return PiSoapManager
     */    
    public function setCookie($name, $value);
    
    /**
     * Set soap localtion
     *
     * @param string $location
     * 
     * @return PiSoapManager
     */    
    public function setLocation($location);    
    
    /**
     * Get the soap client
     *
     * @return \SoapClient
     */
    public function getSoapClient();   
    
    /**
     * Create a PHP SOAP client and configure it
     *
     * @return PiSoapManager
     */
    public function create();
    
    /**
     * Return result of the SOAP call
     *
     * @param string $method
     * @param array  $parameters
     * 
     * @return string|object
     */    
    public function call($method, array $parameters);    
}
