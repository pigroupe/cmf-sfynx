<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Route
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-02-27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Route;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Matcher\Dumper\ApacheMatcherDumper;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Connection;
use Sfynx\CmfBundle\Route\DoctrineRoute;

/**
 * route page management.
 *
 * @subpackage Tool
 * @package    Route
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RouteLoader implements LoaderInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;
    
    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */    
    private $collection;    

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $_connection;    
    
    /**
     * @var array
     */
    private $routes = array();
    
    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container   = $container;
        $this->collection  = new RouteCollection();
        $this->routes      = $this->container->get('pi.route.route_doctrine')->addAllRoutePageCollections();
    }
    
    /**
     * @param string $resource
     * @param null $type
     * @return \Symfony\Component\Routing\RouteCollection
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-27
     */ 
    public function load($resource, $type = null)
    {
        
        // we add all routes in the route collection
        if (is_array($this->routes)) {
            foreach ($this->routes as $key => $route_values) {
                $this->addRouteCollections($route_values['route'], $route_values['locales'], $route_values['defaults'], $route_values['requirements'], array());
            }
//          print_r('<pre>');
//          print_r(var_dump($this->collection->all()));
//          print_r('</pre>');
//          exit;
        }

        // we dump all routes in the cache UrlGenerator
        //$dumper = new ApacheMatcherDumper($this->collection);
        
        return $this->collection;
    }
    
    /**
     *
     * Add all routes register in the table pi_routing.
     * Below examples of routes generated.
     *
     *  <code>
     *
     *     <route id="public_homepage" pattern="/" >
     *         <default key="_controller">SfynxCmfBundle:Frontend:page</default>
     *     </route>
     *         
     *     <route id="page_metiers_conseil_marketing_strategie" >
     *         <locale key="en">/business/consulting</locale>
     *         <locale key="fr">/business/conseil</locale>
     *         <default key="_controller">SfynxCmfBundle:Frontend:page</default>
     *         <requirement key="_method">get|post</requirement>
     *     </route>
     *     
     *     <route id="page_metiers_management_projet" pattern="/business/management" >
     *         <default key="_controller">SfynxCmfBundle:Frontend:page</default>
     *         <requirement key="_method">get|post</requirement>
     *     </route>
     * 
     *  <code>
     * 
     * @param string  $name         The route name
     * @param array   $locales      An array with keys locales and values patterns
     * @param array   $defaults     An array of default parameter values
     * @param array   $requirements An array of requirements for parameters (regexes)
     * @param array   $options      An array of options
     * @return void
     * @access private
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-27
     */ 
    private function addRouteCollections($name, array $locales, array $defaults = array(), array $requirements = array(), array $options = array())
    {
        $locales = array_unique($locales);        
        foreach ($locales as $locale => $pattern) {
            if (count($locales) >=2) {
                $defaults['_locale'] = $locale;
                $locale = '.'.$locale;
            } else {
                $locale = '';
            }
            $this->collection->add($name.$locale, new Route($pattern, $defaults, $requirements, $options));
        }
    } 

    public function supports($resource, $type = null)
    {
        return 'sfynxcmfextra' === $type;
    }
    
    public function getResolver()
    {
    }
    
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // irrelevant to us, since we don't need a resolver
    }    
}
