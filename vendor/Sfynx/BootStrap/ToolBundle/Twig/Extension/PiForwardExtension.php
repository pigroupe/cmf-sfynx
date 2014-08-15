<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Main
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BootStrap\ToolBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Action Functions used in twig
 *
 * @category   Main
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiForwardExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */    
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
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
        return 'admin_forward_extension';
    }    
    
    /**
     * Returns a list of functions to add to the existing list.
     *
     * <code>
     *  {{ renderForward('PiAppAdminBundle:Page:new') }}
     * </code>
     *
     * @return array An array of functions
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getFunctions()
    {
        return array(
                'renderForward'  => new \Twig_Function_Method($this, 'renderForwardFunction'),
        );
    }    

    /**
     * Returns the Response content for a given controller or URI.
     *
     * @param string $controller The controller name
     * @param array  $params    An array of params
     * 
     * @return string
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function renderForwardFunction($controller, $params = array())
    {
        if (strpos($controller, ':') == false) {
            $controller = 'PiAppAdminBundle:Frontend:index';
        }
        $params['lang']   = $this->container->get('request')->getLocale();
        $params['_route'] = $this->container->get('request')->get('_route');
        // this allow Redirect Response in controller action
        $params['_controller'] = $controller;
        $subRequest = $this->container->get('request')->duplicate($_GET, $_POST, $params);
        $response =  $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        
        return $response->getContent();
    }
}