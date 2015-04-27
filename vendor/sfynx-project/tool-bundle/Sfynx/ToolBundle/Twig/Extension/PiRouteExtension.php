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
            'path_url'    => new \Twig_Function_Method($this, 'getUrlByRouteFunction'),
            'match_url'   => new \Twig_Function_Method($this, 'getMatchUrlFunction'),
            'in_paths'    => new \Twig_Function_Method($this, 'inPathsFunction'),
            'route_match' => new \Twig_Function_Method($this, 'isRouteMatchingFunction'), 
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
    
    /**
     * Return the $returnTrue value if the route of the page is include in $paths value, else return the $returnFalse value.
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function inPathsFunction($matches, $returnTrue = '', $returnFalse = '')
    {
        $route = (string) $this->container->get('request')->get('_route');
        $names = explode(':', $matches);
        $is_true = false;        
        if (is_array($names)) {
            foreach ($names as $k => $path) {
                if ($route == $path)
                    $is_true = true;
            }
            if ($is_true) {
                return $returnTrue;
            } else {
                return $returnFalse;
            }            
        } else {
            if ($route == $matches) {
                return $returnTrue;
            } else {
                return $returnFalse;
            }            
        }
    }   
    
    public function isRouteMatchingFunction($matches)
    {
        $current = $this->request->get('_route');
        $path = $this->request->getPathInfo();

        foreach ($matches as $match) {
            if (!is_string($match)) {
                continue;
            }
            // Test Path
            if (substr($match, -1) == "*") {
                // Has a wildcard
                $temp = str_replace("*", "", $match);
                if (strpos($path, $temp) !== false) {
                    return true;
                }
            } else {
                // Doesn't have a wildcard
                if ($match == $path) {
                    return true;
                }
            }

            // Test route name
            if ($current && $current == $match) {
                return true;
            }
        }

        return false;
    }    
}
