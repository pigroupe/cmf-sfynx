<?php
/**
 * This file is part of the <Common> project.
 *
 * @subpackage Common
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

/**
 * Construct a PHP SOAP client
 *
 * <code>
 *     $soapManager = $this->getContainer()->get('sfynx.tool.soapclient_manager');
 *     $soapManager->setWsdl('http://...');
 *     $soapManager->setOptions(array(
 *            "trace" => true,
 *            "soap_version" => SOAP_1_2)
 *     );
 *     $soapManager->setHeaders(array(
 *             0 => array(
 *                'url' => "http://...",
 *                'stringrequest' => "userName",
 *                'content' => "userNameValue"
 *            ),
 *            1 => array(
 *                'url' => "...",
 *                'stringrequest' => "password",
 *                'content' => "passwordValue"
 *            )
 *     )); 
 * 
 *     $soapManager->create();
 *     $oReturn = $soapManager->call("ClassName",array($ClassNameInstance));
 *     var_dump($oReturn);
 * </code>
 * 
 * @subpackage Common
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiSoapClientManager
{
    /**
     * @var \SoapClient $soapClient
     */       
    private $soapClient;
    
    /**
     * @var string $wsdl
     */
    private $wsdl;
    
    /**
     * @var string $options
     */
    private $options = array();    
    
    /**
     * @var array $headers
     */      
    private $headers = array();      
    
    /**
     * @var string $localion
     */    
    private $location;    
    
    /**
     * @var string $username
     */    
    private $username;
    
    /**
     * @var string $password
     */    
    private $password;    
    
    /**
     * @var array $converters
     */      
    private $converters = array();       
    
    /**
     * @var string $classMap
     */    
    private $classMap;
    
    /**
     * @var string $debug
     */    
    private $debug;
    
    /**
     * Constructor.
     *
     * @param string $cacheDir
     * @param Object $logger
     * 
     * @return void
     */    
    public function __construct()
    {
    }
    
    /**
     * Set authentication for accessing the WSDL itself, if that is protected
     * with a password
     *
     * @param string $wsdl URI to the WSDL
     * 
     * @return PiSoapManager
     */
    public function setWsdl($wsdl)
    {
        $this->wsdl = $wsdl;
        
        return $this;
    }    
    
    /**
     * Get the soap client
     *
     * @return \SoapClient
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }        
    
    /**
     * Set authentication for accessing the WSDL itself, if that is protected
     * with a password
     *
     * @param string $username
     * @param string $password
     * 
     * @return PiSoapManager
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        
        return $this;
    }        
    
    /**
     * Set authentication for accessing the WSDL itself, if that is protected
     * with a password
     *
     * @param string $username
     * @param string $password
     * 
     * @return PiSoapManager
     */
    public function setAuth($username, $password)
    {
        $this->options['login']    = $this->username;
        $this->options['password'] = $this->password;
        
        return $this;
    }    
    
    /**
     * Set headers
     *
     * @param array $headers
     * 
     * @return PiSoapManager
     */    
    public function setHeaders(array $headers)
    {
        $this->headers = array();
        foreach ($headers as $header) {
            $this->addHeader($header);
        }
        
        return $this;
    }       
    
    /**
     * Set cookie
     *
     * @param string $name
     * @param string $value
     * 
     * @return PiSoapManager
     */    
    public function setCookie(string $name , string $value )
    {
        if ($this->soapClient instanceof \SoapClient) {
            $this->soapClient->__setCookie($name, $value);
        }
        
        return $this;
    }    
    
    /**
     * Set localtion
     *
     * @param string $location
     * 
     * @return PiSoapManager
     */    
    public function setLocation($location)
    {
        $this->location = $location;
        
        return $this;
    }      
    
    /**
     * Set converters
     *
     * @param array $converters
     * 
     * @return PiSoapManager
     */        
    public function setConverters(array $converters)
    {
        $this->converters = array();
        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }
    }    
    
    /**
     * Set classMap
     *
     * @param array $classMap
     * @param type  $debug
     * 
     * @return PiSoapManager
     */
    public function setClassMap(array $classMap = array(), $debug = false)
    {
        $this->classMap = $classMap;
        $this->debug    = $debug;
        
        $options = array(
            'classmap'   => $this->classMap,
            'typemap'    => $this->createTypeMap(),
            'features'   => SOAP_SINGLE_ELEMENT_ARRAYS,
            'cache_wsdl' => $this->debug ? WSDL_CACHE_NONE : WSDL_CACHE_DISK,
            'trace'      => true
        );
        
        $this->options = array_merge($this->options, $options);
        
        return $this;
    }       
    
    /**
     * Create a PHP SOAP client and configure it
     *
     * @return PiSoapManager
     */
    public function create()
    {
        if (empty($this->wsdl)) {
            throw new \InvalidArgumentException('The wsdl is not defined');
        }
        // we create the client SOAP
        $this->soapClient = new \SoapClient($this->wsdl, $this->options);
        // we add headers
        if(count($this->headers) >= 1) {
            $this->soapClient->__setSoapHeaders($this->headers);
        }
        if(!empty($this->url)) {
            $this->soapClient->__setLocation($this->url);
        }
        
        return $this;
    }
    
    /**
     * Return result of the SOAP call
     *
     * @param string $method
     * @param  array $parameters
     * 
     * @return string|object
     */    
    public function call($method, array $parameters)
    {
        try
        {
            $oReturn = $this->soapClient->__soapCall($method, $parameters);
            unset($this->soapClient);
            
            return $oReturn;
        }
        catch (\SoapFault $fault) 
        {
            print_r("Response: ".$this->soapClient->__getLastResponse()); 
            trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
        } 
    }       
    
    /**
     * Create SOAP type map
     *
     * @return array
     */
    private function createTypeMap()
    {
        $typeMap = array();
        foreach($this->allConverters() as $typeConverter) {
            $typeMap[] = array(
                'type_name' => $typeConverter->getTypeName(),
                'type_ns'   => $typeConverter->getTypeNamespace(),
                'from_xml'  => function($input) use ($typeConverter) {
                    return $typeConverter->convertXmlToPhp($input);
                },
                'to_xml'    => function($input) use ($typeConverter) {
                    return $typeConverter->convertPhpToXml($input);
                },
            );
        }
        return $typeMap;
    }

    /**
     * Return all values of all converters
     * 
     * @access private
     * @return array
     */    
    private function allConverters()
    {
        return array_values($this->converters);
    }  
    
    /**
     * Return if a converter is in the container
     * 
     * @param string $namespace
     * @param string $name
     * 
     * @access private
     * @return boolean
     */     
    private function hasConverter($namespace, $name)
    {
        return isset($this->converters[$namespace.':'.$name]);
    }
    
    /**
     * Return if a converter is in the container
     * 
     * @param TypeConverterInterface $converter
     * 
     * @access private
     * @return void
     */      
    private function addConverter(TypeConverterInterface $converter)
    {
        if ($this->hasConverter($converter->getTypeNamespace(), $converter->getTypeName())) {
            throw new \InvalidArgumentException(sprintf('The converter "%s %s" already exists', $converter->getTypeNamespace(), $converter->getTypeName()));
        }
        $this->converters[$converter->getTypeNamespace().':'.$converter->getTypeName()] = $converter;
    }
    
    /**
     * Return if a converter is in the container
     * 
     * @param array $header
     * 
     * @access private
     * @return void
     */      
    private function addHeader(array $header)
    {
        $this->headers[] = new \SoapHeader($header['url'], 
                            $header['stringrequest'],
                            $header['content']);
    }    
}

class DateTypeConverter implements TypeConverterInterface
{
    public function getTypeNamespace()
    {
        return 'http://www.w3.org/2001/XMLSchema';
    }
    public function getTypeName()
    {
        return 'date';
    }
    public function convertXmlToPhp($data)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($data);
        return new \DateTime($doc->textContent);
    }
    public function convertPhpToXml($data)
    {
        return sprintf('<%1$s>%2$s</%1$s>', $this->getTypeName(), $data->format('Y-m-d'));
    }
}

interface TypeConverterInterface
{
    function getTypeNamespace();
    function getTypeName();
    function convertXmlToPhp($data);
    function convertPhpToXml($data);
}
