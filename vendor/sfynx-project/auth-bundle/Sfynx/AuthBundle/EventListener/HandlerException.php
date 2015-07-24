<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Exception
 * @author     riad hellal <hellal.riad@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Custom Exception handler.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Exception
 * @author     riad hellal <hellal.riad@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class HandlerException
{
    /**
     * @var EngineInterface $templating The templating service
     */
    protected $templating;
    
    /**
     * @var string $locale The locale value
     */
    protected $locale;
    
    /**
     * @var ContainerInterface $container The service container
     */
    protected $container;
    
    /**
     * @var \AppKernel $kernel Kernel service
     */
    protected $kernel;    
    
    /**
     * Constructor.
     * 
     * @param EngineInterface    $templating The templating service
     * @param \AppKernel         $kernel     The kernel service
     * @param ContainerInterface $container  The containerservice
     */
    public function __construct(
        EngineInterface $templating,
        \AppKernel $kernel,
        ContainerInterface $container
    ) {
        $this->container  = $container;
        $this->templating = $templating;
        $this->locale     = $this->container->get('request')->getLocale();
        $this->kernel     = $kernel;
    }

    /**
     * Event handler that renders not found page
     * in case of a NotFoundHttpException
     *
     * @param GetResponseForExceptionEvent $event
     *
     * @access public
     * @return void
     * @author Riad Hellal <hellal.riad@gmail.com>
     */    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->request = $event->getRequest($event);
        // provide the better way to display a enhanced error page only in prod environment, if you want
        if (in_array($this->kernel->getEnvironment(), array('test', 'prod'))) {
            // exception object
            $exception = $event->getException();
            // new Response object
            $response = new Response();
            
            //$requestDuplicate = $this->request->duplicate(null, null, ['_controller' => 'MyAppSiteBundle:Default:exception']);
            //$response = $this->kernel->handle($requestDuplicate, HttpKernelInterface::SUB_REQUEST);

            if ($this->container->hasParameter('sfynx.auth.theme.layout.error.html')
                    && ($this->container->getParameter('sfynx.auth.theme.layout.error.html') != "")) {
                $path_error_file = realpath($this->kernel->locateResource($this->container->getParameter('sfynx.auth.theme.layout.error.html')));
                $response->setContent(file_get_contents($path_error_file));
            } else {
                if ($this->container->hasParameter('sfynx.auth.theme.layout.error.route_name')
                        && ($this->container->getParameter('sfynx.auth.theme.layout.error.route_name')!= "")
                ) {
                    $route_name = $this->container->getParameter('sfynx.auth.theme.layout.error.route_name');
                } else {
                    $route_name = 'error_404';
                }
                $url      = $this->container->get('sfynx.tool.route.factory')->getRoute($route_name, array('locale'=> $this->locale));
                $content  = \Sfynx\ToolBundle\Util\PiFileManager::getCurl('/'.$url, null, null, $this->request->getUriForPath(''));
                $response->setContent($content);
            }
            // HttpExceptionInterface is a special type of exception
            // that holds status code and header details
            if (method_exists($exception, "getStatusCode")) {
                $response->setStatusCode($exception->getStatusCode());
            } else {
                $response->setStatusCode('404');
            }
            if (method_exists($response, "getHeaders")) {
                $response->headers->replace($exception->getHeaders());
            }
            // set the new $response object to the $event
            $event->setResponse($response);
        }
    }
}