<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Controllers
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Controller;

use BootStrap\TranslationBundle\Controller\abstractController;
use PiApp\AdminBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use PiApp\AdminBundle\Entity\Enquiry;
use PiApp\AdminBundle\Form\EnquiryType;
use PiApp\AdminBundle\Entity\Page as Page;
use PiApp\AdminBundle\Entity\TranslationPage;

use PiApp\AdminBundle\Event\ResponseEvent;
use PiApp\AdminBundle\PiAppAdminEvents;

/**
 * Frontend controller.
 *
 * @category   Admin_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class FrontendController extends abstractController
{
    /**
     * Displays a page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-24
     */
    public function pageAction()
    {
        $route   = $this->container->get('request')->get('route_name');
        // we get the page manager
        $pageManager      = $this->get('pi_app_admin.manager.page');
        // we get the route name
        if (empty($route)) {
            $route = $this->container->get('request')->get('_route');
        }
        // we set the object Translation Page by route
        $pageManager->setPageByRoute($route);
        // we return the render (cache or not)
        $response = $pageManager->render();
        
        return $response;
    }
    
    /**
     * Execute an applying esi widget.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2014-01-16
     */
    public function esipageAction($method, $serviceName, $id, $lang, $params, $server, $key)
    {
    	$method 	 = $this->container->get('pi_app_admin.twig.extension.tool')->decryptFilter($method, $key);
    	$serviceName = $this->container->get('pi_app_admin.twig.extension.tool')->decryptFilter($serviceName, $key);
    	$id 		 = $this->container->get('pi_app_admin.twig.extension.tool')->decryptFilter($id, $key);
    	$lang 		 = $this->container->get('pi_app_admin.twig.extension.tool')->decryptFilter($lang, $key);
    	$params		 = json_decode($this->container->get('pi_app_admin.twig.extension.tool')->decryptFilter($params, $key), true);
    	$options     = json_decode($this->container->get('pi_app_admin.twig.extension.tool')->decryptFilter($server, $key), true);
    	// we get the page manager
    	$pageManager = $this->get('pi_app_admin.manager.page');
    	// we set the ESI page result
    	$response    = $pageManager->renderESISource($serviceName, $method, $id, $lang, $params, $options);
    	
    	//     	print_r($server['REQUEST_URI']);
    	//     	print_r($serviceName);
    	//     	print_r($method);
    	//     	print_r($id);
    	//     	print_r($lang);
    	//     	print_r($params);
    	//     	exit;    	
    	//$route_name = $this->container->get('request')->get('_route');
    	//$route_name = $this->container->get('request')->attributes->get('_route');
    	//$path_info = $this->container->get('request')->getRequestUri();
    	//print_r($path_info);exit;

    	return $response;
    }    
    
    /**
     * Copy the referer page.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2013-12-017
     */
    public function copypageAction()
    {
    	try {
    		$locale      = $this->container->get('request')->getLocale();
    		$data        = $this->container->get('bootstrap.RouteTranslator.factory')->getRefererRoute($locale, array('result' => 'match'));
    		// we get the page manager
    		$pageManager = $this->get('pi_app_admin.manager.page');
    		// we get the object Page by route
    		$page        = $pageManager->setPageByRoute($data['_route'], true);
    		// we set the result
    		if ($page instanceof Page){
    			$new_url = $pageManager->copyPage();
    		}
    	} catch (\Exception $e) {
    		$new_url = $this->container->get('router')->generate('home_page');
    	}
    	
    	return new RedirectResponse($new_url);
    }     

    /**
     * Refresh a page with all these languages
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-04-02
     */
    public function refreshpageAction()
    {
    	try {
    		$lang        = $this->container->get('request')->getLocale();
    		$data        = $this->container->get('bootstrap.RouteTranslator.factory')->getRefererRoute($lang, array('result' => 'match'));
    		$new_url     = $this->container->get('bootstrap.RouteTranslator.factory')->getRefererRoute($lang);
    		// we get the page manager
    		$pageManager = $this->get('pi_app_admin.manager.page');
    		// we get the object Page by route
    		$page        = $pageManager->setPageByRoute($data['_route'], true);
    		// we set the result
    		if ($page instanceof Page){
    			$pageManager->cacheRefresh();
    		}
    		$this->container->get('request')->setLocale($lang);
    	} catch (\Exception $e) {
    		$new_url = $this->container->get('router')->generate('home_page');
    	}
    
    	return new RedirectResponse($new_url);
    }    
    
    /**
     * Indexation mamanger of a page (archiving or delete)
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-02
     */
    public function indexationAction($action)
    {
        $lang    = $this->container->get('request')->getLocale();
        $data    = $this->container->get('bootstrap.RouteTranslator.factory')->getRefererRoute($lang, array('result' => 'match'));
        $new_url = $this->container->get('bootstrap.RouteTranslator.factory')->getRefererRoute($lang);    
        // we get the page manager
        $pageManager = $this->get('pi_app_admin.manager.page');
        // we get the object Page by route
        $page        = $pageManager->setPageByRoute($data['_route'], true);        
        // we set the result
        if ($page instanceof Page) {
            switch ($action) {
                case ('archiving') :
                    $this->container->get('pi_app_admin.manager.search_lucene')->indexPage($page);
                    return new Response('archiving-ok');
                    break;
                case ('delete') :
                    $this->container->get('pi_app_admin.manager.search_lucene')->deletePage($page);
                    return new Response('delete-archiving-ok');
                    break;
                default:
                    // deafault
                    break;
            }
        }
        
        return new Response('no');
    }

    /**
     * Admin Ajax action management of all blocks and widgets of a page
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-05-04
     */    
    public function urlmanagementAction()
    {
        $request = $this->container->get('request');    
        if ($request->isXmlHttpRequest()){
            $urls        = null;
            //
            if ($request->query->has('id'))        $id        = $request->query->get('id');        else    $id        = null;
            if ($request->query->has('type'))      $type      = $request->query->get('type');      else    $type      = null;
            if ($request->query->has('routename')) $routename = $request->query->get('routename'); else    $routename = "";            
            if ($request->query->has('action'))    $action    = $request->query->get('action');    else    $action    = "no";            
            // we get the page manager
            $pageManager      = $this->get('pi_app_admin.manager.page');            
            switch ($type){
                case 'routename':
                    // we return the url result of the routename
                    $urls[$action]    = $this->get('bootstrap.RouteTranslator.factory')->getRoute($routename);
                    break;                
                case 'page':
                    // we get the object Translation Page by route
                    $page     = $pageManager->setPageByRoute($routename);
                    if ($page instanceof Page) {
                        $urls = $pageManager->getUrlByType('page', $page);
                    } else {
                        $urls = $pageManager->getUrlByType('page');
                    }
                    // we get all the urls in order to the management of widgets.
                    $urls     = $pageManager->getUrlByType('page', $page);
                    break;                
                case 'block':
                    // we get the object block by id
                    $block    = $pageManager->getBlockById($id);                    
                    // we get all the urls in order to the management of a block.
                    $urls     = $pageManager->getUrlByType('block', $block);                    
                    break;
                case 'widget':
                    // we get the object widget by id
                    $widget   = $pageManager->getWidgetById($id);                    
                    // we get all the urls in order to the management of a widget.
                    $urls     = $pageManager->getUrlByType('widget', $widget);                    
                    break;
            }
            // we return the desired url
            $values[0]['url'] = $urls[$action];
            $response = new Response(json_encode($values));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;               
        } else {
            throw ControllerException::callAjaxOnlySupported('urlmanagement');
        }
    }
    
    /**
     * Import action of all widgets
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-22
     */
    public function importmanagementAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $locale   = $this->container->get('request')->getLocale();
        $NoLayout = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "importmanagement.html.twig"; else $template = "importmanagement_ajax.html.twig";          
        
        return $this->render("PiAppAdminBundle:Frontend:$template", array(
                'NoLayout'    => $NoLayout,
        ));        
    }  

    /**
     * Parse a file and returns the contents
     *
     * @param string    $file         file name consists of: web_bundle_piappadmin_css_screen__css for express this path : web/bundle/piappadmin/css/screen.css
     * @return string    content of the file
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-12
     */
    public function contentfileAction($file)
    {
    	$fileFormatter    = $this->container->get('pi_app_admin.file_manager');
    
    	return $fileFormatter->getContentCodeFile($file);
    }    

    /**
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return json
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-22
     */
    public function chainedAction()
    {
        $values[""] = "--";
        $values[""]             = "--";
        $values["text"]         = "text";
        $values["snippet"]         = "snippet";
        //
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
    /**
     *
     * @author Riad HELLAL <hellal.riad@gmail.com>
     * @return type
     */
    public function contactAction()
    {
        $request = $this->getRequest();
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);
        if ($request->getMethod() == 'POST') {
            $form->bind($request);    
            if ($form->isValid()) {
                // action sending an email
                $message = \Swift_Message::newInstance()
                ->setSubject('Contact enquiry from sfynx')
                ->setFrom('enquiries@sfynx.dev')
                ->setTo('email@email.com')
                ->setBody($this->renderView('PiAppAdminBundle:Frontend:contactEmail.txt.twig', array('enquiry' => $enquiry)));
                if ($this->get('mailer')->send($message)) {
                    // Redirect - This is important to prevent users re-posting
                    // the form if they refresh the page
                    $this->get('request')->getSession()->getFlashBag()->add('success', 'Your contact enquiry was successfully sent. Thank you!');
                } else {
                    $this->get('request')->getSession()->getFlashBag()->add('notice', 'Your contact enquiry was NOT sent. Thank you!');
                }
                
                return $this->redirect($this->generateUrl('public_contact'));
            }
        }
    
        return $this->render('PiAppAdminBundle:Frontend:contact.html.twig', array(
                'form' => $form->createView()
        ));
    
    }    
        
}
