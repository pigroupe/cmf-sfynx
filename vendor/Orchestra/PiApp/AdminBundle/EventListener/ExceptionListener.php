<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 * @author riad hellal <hellal.riad@gmail.com>
 * @since 2013-04-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Custom Exception handler.
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 *
 * @author riad hellal <hellal.riad@gmail.com>
 */
class ExceptionListener
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
            $url      = $this->container->get('bootstrap.RouteTranslator.factory')->getRoute('error_404', array('locale'=> $this->local));
            $content  = \PiApp\AdminBundle\Util\PiFileManager::getCurl('/'.$url, null, null, $this->request->getUriForPath(''));
            // set response content
            $response->setContent($content);
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