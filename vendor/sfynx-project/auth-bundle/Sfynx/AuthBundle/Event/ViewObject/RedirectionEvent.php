<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
 * @package    Event
 * @subpackage ViewObject
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
namespace Sfynx\AuthBundle\Event\ViewObject;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\ToolBundle\Builder\RouteTranslatorFactoryInterface;

/**
 * Redirection event of connection user.
 *
 * @category   Auth
 * @package    Event
 * @subpackage ViewObject
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RedirectionEvent extends Event
{    
    /**
     * @var \Sfynx\ToolBundle\Route\RouteTranslatorFactory $router
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
     * @return Response
     */
    public function getResponse() 
    {
        $redirection = $this->getRedirection();
        // we deal with the general case with a non ajax connection.
        if (!empty($redirection)) {
            $response = new RedirectResponse($redirection);
        } elseif (!empty($this->redirect)) {
            $response = new RedirectResponse($this->router->getRoute($this->getRouteName()));
    	}   
        
        return $response;
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
            return $this->router->getRoute($this->getRouteName(), $this->getParams());
        } else {
            return $this->url;
        }
    }
}
