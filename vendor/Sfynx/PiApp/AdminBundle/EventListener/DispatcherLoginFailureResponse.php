<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-07-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernel;

use PiApp\AdminBundle\Event\ResponseEvent;

/**
 * Response handler of login failure connection
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DispatcherLoginFailureResponse
{
   /**
    * @var \Symfony\Component\DependencyInjection\ContainerInterface
    */
   protected $container;  

   public $login_failure = true;
   public $login_failure_time_expire = 3600;
   public $login_failure_connection_attempts = 3;

   /**
    * Constructor.
    *
    * @param string $defaultLocale	Locale value
    */   
   public function __construct(ContainerInterface $container)
   {
       $this->container     = $container;  
   }

   /**
    * Invoked to modify the controller that should be executed.
    *
    * @param FilterControllerEvent $event The event
    *
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */   
   public function onPiLoginFailureResponse(ResponseEvent $event)
   {
       $response = $event->getResponse();
       if ($this->login_failure && !empty($this->login_failure_time_expire)) {
           $value = $this->getKeyValue();
           if (!empty($value)) {
               if ( $value == 'stop-client') {
               } elseif (intval($value) >= $this->login_failure_connection_attempts) {
                   $this->container->get("pi_filecache")->getClient()->changeValue($this->setKey(), 'stop-client');
               } else {
                   $this->container->get("pi_filecache")->getClient()->changeValue($this->setKey(), $value+1);
               }
           } else {
               $this->container->get("pi_filecache")->set($this->setKey(), 1, $this->getTtl());
           }
       }       
       $event->setResponse($response);
   }

   /**
    * We return the value of the key of the failure connection.
    *
    * @return int
    *
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function getKeyValue()
   {
       // we create path
       $this->setCachePath();
       // we return the value of the failure cache file
       return $this->container->get("pi_filecache")->get($this->setKey(), $this->getTtl());
   }
   
   /**
    * We return the the key of the failure connection.
    *
    * @return string
    *
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function setKey()
   {
       // we get the username login
       if ($this->container->get('request')->getSession()->has('login-username')) {
           $username = $this->container->get('request')->getSession()->get('login-username') . '-';
       } else {
           $username = "";
       }
       // we return the key ID of failure connection
       return $username . $this->container->get('request')->getClientIp() . '-' .$_SERVER['HTTP_USER_AGENT'];
   }   
   
   /**
    * We return the ttl of the configuration.
    *
    * @return int
    *
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */   
   public function getTtl()
   {
       // we create ttl of secure login failure by client
       if (is_numeric($this->login_failure_time_expire)) {
           return intVal($this->login_failure_time_expire);
       } else {
           return 3600;
       }
   }
   
   /**
    * We set the path of the all failure login filecache.
    *
    * @return void
    *
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function setCachePath()
   {
       // we create path
       $dossier = $this->container->getParameter("kernel.root_dir")."/cache/loginfailure/";
       \PiApp\AdminBundle\Util\PiFileManager::mkdirr($dossier, 0777);
       $this->container->get("pi_filecache")->getClient()->setPath($dossier);
   }

}