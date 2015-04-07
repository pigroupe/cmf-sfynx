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

use \Sfynx\ToolBundle\Util\Soap\PiSoapManagerInterface;
use \Sfynx\ToolBundle\Util\Soap\PiSoapModelInterface;

/**
 * Construct a soap proxy class
 *
 * @subpackage Tool
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class PiAbstractSoapModel implements PiSoapModelInterface
{
    /**
     * @var PiSoapManagerInterface $soapmanager
     */    
    protected $soapmanager;
    
    /**
     * @var null|array|object $option
     */    
    protected $option = null;    
    
    /**
     * @var string $method
     */    
    protected $method = "";
    
    /**
     * @var string $method
     */    
    protected $parameters = null;    
    
    /**
     * @var string $wsdl
     */
    protected $wsdl = "";
    
    /**
     * @var string $options
     */
    protected $soap_options = array();    
    
    /**
     * @var array $headers
     */      
    protected $headers = array();      
    
    /**
     * Set soap client
     *
     * @param PiSoapManagerInterface $soapmanager
     * 
     * @return void
     */    
    public function setSoapManager(PiSoapManagerInterface $soapmanager)
    {
        $this->soapmanager = $soapmanager;
    }
    
    /**
     * Set model option
     *
     * @param null|array|object $option
     * 
     * @return void
     */       
    public function setOption($option)
    {
        $this->option = $option;
    }    
    
    /**
     * Set create model
     *
     * @return void
     */      
    public function create()
    {
        $this->soapmanager->setWsdl($this->wsdl);
        $this->soapmanager->setOptions($this->soap_options);
        $this->soapmanager->setHeaders($this->headers);        
        $this->soapmanager->create();
    }  
    
    /**
     * Set call model
     *
     * @return object The result of the soap call
     */       
    public function call()
    {
        $this->setParameters();
        
        return $this->soapmanager->call($this->method, array($this->parameters));
    }  
    
    protected function setParameters()
    {
        throw new \InvalidArgumentException('setParameters method has to be defined');
    }      
}
