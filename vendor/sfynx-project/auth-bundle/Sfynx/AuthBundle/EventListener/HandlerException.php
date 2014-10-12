<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Exception
 * @author     riad hellal <hellal.riad@gmail.com>
 * @copyright  2014 Pi-groupe
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

/**
 * Custom Exception handler.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Exception
 * @author     riad hellal <hellal.riad@gmail.com>
 * @copyright  2014 Pi-groupe
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class HandlerException
{
    /**
     * @var EngineInterface
     */
    protected $templating;
    
    /**
     * @var \AppKernel
     */
    protected $kernel;
    
    /**
     * @var string
     */
    protected $local;
    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor.
     * 
     * @param EngineInterface $templating
     * @param \AppKernel $kernel
     * @param ContainerInterface $container The service container
     */
    public function __construct(EngineInterface $templating, \AppKernel $kernel, ContainerInterface $container)
    {
        $this->container  = $container;
        $this->templating = $templating;
        $this->kernel = $kernel;
        $this->local = $this->container->get('request')->getLocale();
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
        if ('prod' == $this->kernel->getEnvironment()) {
            // exception object
            $exception = $event->getException();
            // new Response object
            $response = new Response();
            //
            if ($this->container->hasParameter('sfynx.auth.theme.layout.error.html') && ($this->container->getParameter('sfynx.auth.theme.layout.error.html') != "")) {
                $path_error_file = realpath($this->container->get('kernel')->locateResource($this->container->getParameter('sfynx.auth.theme.layout.error.html')));
                $response->setContent(file_get_contents($path_error_file));
            } else {
                if ($this->container->hasParameter('sfynx.auth.theme.layout.error.route_name') && ($this->container->getParameter('sfynx.auth.theme.layout.error.route_name' != ""))) {
                    $route_name = $this->container->getParameter('sfynx.auth.theme.layout.error.route_name');
                } else {
                    $route_name = 'error_404';
                }
                $url      = $this->container->get('sfynx.tool.route.factory')->getRoute($route_name, array('locale'=> $this->local));
                $content  = \Sfynx\ToolBundle\Util\PiFileManager::getCurl('/'.$url, null, null, $this->request->getUriForPath(''));
                $response->setContent($content);
            }
            // HttpExceptionInterface is a special type of exception
            // that holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(500);
            }
            // set the new $response object to the $event
            $event->setResponse($response);
        }
    }
}