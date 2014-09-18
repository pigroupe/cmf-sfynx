<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
 * @package    EventListener
 * @author riad hellal <hellal.riad@gmail.com>
 * @since 2013-04-18
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
 * @category   Auth
 * @package    EventListener
 *
 * @author riad hellal <hellal.riad@gmail.com>
 */
class HandlerException
{
    protected $templating;
    protected $kernel;
    protected $local;
    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(EngineInterface $templating, $kernel, ContainerInterface $container)
    {
        $this->container  = $container;
        $this->templating = $templating;
        $this->kernel = $kernel;
        $this->local = $this->container->get('request')->getLocale();
    }

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