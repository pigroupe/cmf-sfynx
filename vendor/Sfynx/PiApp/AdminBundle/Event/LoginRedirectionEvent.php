<?php

/**
 * This file is part of the <Admin> project.
 *
 * @category   Bundle
 * @package    PiApp
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-07-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use BootStrap\TranslationBundle\Builder\RouteTranslatorFactoryInterface;

/**
 * Redirection event of connection user.
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class LoginRedirectionEvent extends Event
{
    /**
     * @var \BootStrap\TranslationBundle\Route\RouteTranslatorFactory $router
     */
    private $router;
    
    /**
     * @var $route_name
     */
    private $route_name;
    
    /**
     * @var $params
     */
    private $params;
    
    /**
     * @var $url
     */
    private $url;

    public function __construct( RouteTranslatorFactoryInterface $router, $routeName = '', $params = null, $url = '')
    {
        $this->router     = $router;
        $this->route_name = $routeName;
        $this->params     = $params;
    }

    /**
     * @return route name
     */
    public function getRouteName()
    {
        return $this->route_name;
    }
    
    /**
     * @return void
     */
    public function setRouteName($routeName)
    {
    	$this->route_name = $routeName;
    }    

    /**
     * @return  params
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * @return  void
     */
    public function setParams(array $params)
    {
    	$this->params = $params;
    }    
    
    /**
     * @return  url
     */
    public function getUrl()
    {
    	return $this->url;
    }

    /**
     * @return  void
     */
    public function setUrl($url)
    {
    	$this->url = $url;
    }    
    
    public function getRedirection() 
    {
        if (empty($this->url) && !empty($this->route_name)) {
            return   $this->router->getRoute($this->getRouteName(), $this->getParams());
        } else {
            return $this->url;
        }
    }
}
