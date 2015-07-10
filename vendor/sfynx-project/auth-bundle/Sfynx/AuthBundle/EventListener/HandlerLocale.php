<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Request
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Custom locale handler.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Request
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class HandlerLocale
{
    /**
     * @var string
     */    
   protected $defaultLocale;
   
   /**
    * @var \Symfony\Component\DependencyInjection\ContainerInterface
    */
   protected $container;   

   /**
    * Constructor.
    *
    * @param string             $defaultLocale	Locale value
    * @param ContainerInterface $container      The container service
    */   
   public function __construct(ContainerInterface $container, $defaultLocale = 'en')
   {
       $this->defaultLocale = $defaultLocale;   
       $this->container     = $container;  
   }

   /**
    * Invoked to modify the controller that should be executed.
    *
    * @param GetResponseEvent $event The event
    * 
    * @access public
    * @return null|void
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */   
   public function onKernelRequest(GetResponseEvent $event)
   {
       if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
           // ne rien faire si ce n'est pas la requête principale
           return;
       }       
       $this->request = $event->getRequest($event);
       //if (!$this->request->hasPreviousSession()) {
       //    return;
       //}
   	   // print_r('priority 1');       
       // we set locale
       $locale = $this->request->cookies->has('_locale');
       $localevalue = $this->request->cookies->get('_locale');
       $is_switch_language_browser_authorized    = $this->container->getParameter('sfynx.auth.browser.switch_language_authorized');
       // Sets the user local value.
       if ($is_switch_language_browser_authorized && !$locale) {
           $lang_value  = $this->container->get('request')->getPreferredLanguage();
           $all_locales = $this->container->get('sfynx.auth.locale_manager')->getAllLocales();
           if (in_array($lang_value, $all_locales)) {
               $this->request->setLocale($lang_value);
               $_GET['_locale'] = $lang_value;

               return;
           }
       }
       if ($locale && !empty($localevalue)) {
           $this->request->attributes->set('_locale', $localevalue);
           $this->request->setLocale($localevalue);
           $_GET['_locale'] = $localevalue;
       } else {
           $this->request->attributes->set('_locale', $this->defaultLocale);
           $this->request->setLocale($this->defaultLocale);
           $_GET['_locale'] = $this->defaultLocale;   
       }
   }
}
