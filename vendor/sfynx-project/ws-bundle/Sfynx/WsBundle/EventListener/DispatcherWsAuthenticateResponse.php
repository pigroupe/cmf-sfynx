<?php
/**
 * This file is part of the <Ws> project.
 *
 * @subpackage Dispatcher
 * @package    Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-02-06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\AuthBundle\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Response handler of authenticate response
 *
 * @subpackage Dispatcher
 * @package    Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DispatcherWsAuthenticateResponse
{
   /**
    * @var ContainerInterface $container
    */
   protected $container; 

   /**
    * Constructor.
    *
    * @param ContainerInterface $container
    */   
   public function __construct(ContainerInterface $container)
   {
       $this->container = $container;  
   }

   /**
    * Invoked to modify the controller that should be executed.
    *
    * @param ResponseEvent $event The event
    * 
    * @return void
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */   
   public function onPiWsAuthenticateResponse(ResponseEvent $event)
   {
        $response   = $event->getResponse();
        $dateExpire = $event->getDateExpire();
        $user       = $event->getUser();
        $app_id     = $this->container->getParameter('sfynx.core.cookies.application_id');
        if ($app_id 
               && !empty($app_id) 
               && $this->container->hasParameter('ws.auth')
        ) {
            $config_ws     = $this->container->getParameter('ws.auth');
            $key           = $config_ws['handlers']['getpermisssion']['key'];
            $userId        = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($user->getId(), $key);
            $applicationId = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($app_id, $key);
            $response->headers->setCookie(new Cookie('sfynx-ws-user-id', $userId, $dateExpire));
            $response->headers->setCookie(new Cookie('sfynx-ws-application-id', $applicationId, $dateExpire));
            $response->headers->setCookie(new Cookie('sfynx-ws-key', $key, $dateExpire));
        }     
        $event->setResponse($response);
   }  
}
