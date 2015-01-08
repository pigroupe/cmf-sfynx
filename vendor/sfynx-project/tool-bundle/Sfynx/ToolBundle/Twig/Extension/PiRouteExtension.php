<?php
/**
 * This file is part of the <Tool> project.
 * 
 * @subpackage   Tool
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Routing Functions used in twig
 *
 * @subpackage   Tool
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiRouteExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */    
    private $container;

    /**
     * Constructor.
     *
     * @param Containe service Manager
     */    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getName()
    {
        return 'sfynx_tool_route_extension';
    }    

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getFunctions()
    {
        return array(
            'path_url'        => new \Twig_Function_Method($this, 'getUrlByRouteFunction'),
            'match_url'       => new \Twig_Function_Method($this, 'getMatchUrlFunction'),
        );
    }
    
    /**
     * Callbacks
     */    

    /**
     * Return the url of a route, with or without a locale value
     *
     * @param string $routeName
     * @param string $params
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUrlByRouteFunction($routeName, $params = null)
    {
        try {
            $url_route = $this->container->get('sfynx.tool.route.factory')->getRoute($routeName, $params);
        } catch (\Exception $e) {
            $url_route = "";
        }
        
        return $url_route;
    }
    
    /**
     * Return the url of a route, with or without a locale value
     *
     * @param string $pathInfo
     * @param string $params
     *
     * @return array
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getMatchUrlFunction($pathInfo)
    {
        try {
            $match    = $this->container->get('be_simple_i18n_routing.router')->match($pathInfo);
        } catch (\Exception $e) {
            $match    = array();
        }
        
        return $match;
    }    
}