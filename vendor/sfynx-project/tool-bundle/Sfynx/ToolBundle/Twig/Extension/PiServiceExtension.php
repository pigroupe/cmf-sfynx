<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Action Functions used in twig
 *
 * @subpackage Tool
 * @package    Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiServiceExtension extends \Twig_Extension
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
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getName()
    {
        return 'sfynx_tool_service_extension';
    }    
    
    /**
     * Returns a list of functions to add to the existing list.
     *
     * <code>
     *  {{ getService('sfynx.tool.string_manager').random(8) }}
     * </code>
     *
     * @return array An array of functions
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getFunctions()
    {
        return array(
                'getService'   => new \Twig_Function_Method($this, 'getServiceFunction'),
                'getParameter' => new \Twig_Function_Method($this, 'getParameterFunction'),
        );
    }    

    /**
     * Returns the service instance given in param.
     *
     * @param string $service The service name
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getServiceFunction($service)
    {
        if ($this->container->has($service)) {
            return $this->container->get($service);
        } else {
            throw new \InvalidArgumentException("The service given in param isn't register !");
        }
    }
    
    /**
     * Returns the service instance given in param.
     *
     * @param string $name The parameter name
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getParameterFunction($name)
    {
        if ($this->container->hasParameter($name)) {
            return $this->container->getParameter($name);
        } else {
            throw new \InvalidArgumentException("The parameter given in param isn't register !");
        }
    }    
}
