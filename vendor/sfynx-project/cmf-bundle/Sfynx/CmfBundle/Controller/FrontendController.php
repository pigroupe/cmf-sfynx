<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Admin_Controllers
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sfynx\CmfBundle\Controller\CmfabstractController;
use Sfynx\ToolBundle\Exception\ControllerException;
use Sfynx\CmfBundle\Entity\Enquiry;
use Sfynx\CmfBundle\Form\EnquiryType;
use Sfynx\CmfBundle\Entity\Page as Page;

/**
 * Frontend controller.
 *
 * @subpackage Admin_Controllers
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com> 
 */
class FrontendController extends CmfabstractController
{
    /**
     * Displays a page
     * 
     * @return Response
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-24
     */
    public function pageAction()
    {
        // $timer = $this->container->get('sfynx.tool.timer_manager')->flush();
        // we get the route name of the page
        $route = $this->container->get('request')->get('route_name');
        // we get the page manager
        $pageManager = $this->get('pi_app_admin.manager.page');
        // we get the route name
        if (empty($route)) {
            $route = $this->container->get('request')->get('_route');
        }
        // we set the object Translation Page by route
        // $timer->start('timer_setPageByRoute');
        $pageManager->setPageByRoute($route, false);
        // we return the render (cache or not)
        // $timer->start('timer_pageManager_render', 'timer_setPageByRoute');
        $response = $pageManager->render('', false);
        // print_r($timer->reporting());
        
        return $response;
    }
    
    /**
     * Execute an applying esi widget.
     *
     * @return Response
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-01-16
     */
    public function esipageAction($method, $serviceName, $id, $lang, $params, $server, $key)
    {
    	$method      = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($method, $key);
    	$serviceName = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($serviceName, $key);
    	$id          = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($id, $key);
    	$lang        = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($lang, $key);
    	$params      = json_decode($this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($params, $key), true);
    	$options     = json_decode($this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($server, $key), true);
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
     * @return RedirectResponse
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2013-12-017
     */
    public function copypageAction()
    {
    	try {
            $locale = $this->container->get('request')->getLocale();
            $data   = $this->container->get('sfynx.tool.route.factory')->getRefererRoute($locale, array('result' => 'match'));
            // we get the page manager
            $pageManager = $this->get('pi_app_admin.manager.page');
            // we get the object Page by route
            $page   = $pageManager->setPageByRoute($data['_route'], true);
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
     * @return RedirectResponse
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-04-02
     */
    public function refreshpageAction()
    {
    	try {
            $lang        = $this->container->get('request')->getLocale();
            $data        = $this->container->get('sfynx.tool.route.factory')->getRefererRoute($lang, array('result' => 'match'));
            $new_url     = $this->container->get('sfynx.tool.route.factory')->getRefererRoute($lang);
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
     * @return Response
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-06-02
     */
    public function indexationAction($action)
    {
        $lang    = $this->container->get('request')->getLocale();
        $data    = $this->container->get('sfynx.tool.route.factory')->getRefererRoute($lang, array('result' => 'match'));
        $new_url = $this->container->get('sfynx.tool.route.factory')->getRefererRoute($lang);    
        // we get the page manager
        $pageManager = $this->get('pi_app_admin.manager.page');
        // we get the object Page by route
        $page        = $pageManager->setPageByRoute($data['_route'], true);        
        // we set the result
        if ($page instanceof Page) {
            if ($action == 'archiving') {
                $this->container->get('pi_app_admin.manager.search_lucene')->indexPage($page);
                
                return new Response('archiving-ok');
            } elseif ($action == 'delete') {
                $this->container->get('pi_app_admin.manager.search_lucene')->deletePage($page);
                
                return new Response('delete-archiving-ok');
            }
        }
        
        return new Response('no');
    }

    /**
     * Admin Ajax action management of all blocks and widgets of a page
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-05-04
     */    
    public function urlmanagementAction()
    {
        $request = $this->container->get('request');    
        if ($request->isXmlHttpRequest()){
            $urls = null;
            //
            if ($request->query->has('id'))        $id        = $request->query->get('id');        else    $id        = null;
            if ($request->query->has('type'))      $type      = $request->query->get('type');      else    $type      = null;
            if ($request->query->has('routename')) $routename = $request->query->get('routename'); else    $routename = "";            
            if ($request->query->has('action'))    $action    = $request->query->get('action');    else    $action    = "no";            
            // we get the page manager
            $pageManager = $this->get('pi_app_admin.manager.page');            
            //
            if ($type == 'routename') {
                // we return the url result of the routename
                $urls[$action]    = $this->get('sfynx.tool.route.factory')->getRoute($routename);
            } elseif ($type == 'page') {
                // we get the object Translation Page by route
                $page     = $pageManager->setPageByRoute($routename);
                if ($page instanceof Page) {
                    $urls = $pageManager->getUrlByType('page', $page);
                } else {
                    $urls = $pageManager->getUrlByType('page');
                }
                // we get all the urls in order to the management of widgets.l
                $urls     = $pageManager->getUrlByType('page', $page);
            } elseif ($type == 'block') {
                // we get the object block by id
                $block    = $pageManager->getBlockById($id);                    
                // we get all the urls in order to the management of a block.
                $urls     = $pageManager->getUrlByType('block', $block);    
            } elseif ($type == 'widget') {
                // we get the object widget by id
                $widget   = $pageManager->getWidgetById($id);                    
                // we get all the urls in order to the management of a widget.
                $urls     = $pageManager->getUrlByType('widget', $widget);                 
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
     * @return Response
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-06-22
     */
    public function importmanagementAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $locale   = $this->container->get('request')->getLocale();
        $NoLayout = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout) {
            $template = "importmanagement.html.twig"; 
        } else {
            $template = "importmanagement_ajax.html.twig";          
        }
        
        return $this->render("SfynxCmfBundle:Frontend:$template", array(
            'NoLayout'    => $NoLayout,
        ));        
    }  

    /**
     * Parse a file and returns the contents
     *
     * @param string $file file name consists of: web_bundle_sfynxtemplate_css_screen__css for express this path : web/bundle/sfynxtemplate/css/screen.css
     * 
     * @return string content of the file
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-12
     */
    public function contentfileAction($file)
    {
    	$fileFormatter    = $this->container->get('sfynx.tool.file_manager');
    
    	return $fileFormatter->getContentCodeFile($file);
    }    

    /**
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return json
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-22
     */
    public function chainedAction()
    {
        $values[""]        = "--";
        $values[""]        = "--";
        $values["text"]    = "text";
        $values["snippet"] = "snippet";
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
                ->setBody($this->renderView('SfynxCmfBundle:Frontend:contactEmail.txt.twig', array('enquiry' => $enquiry)));
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
    
        return $this->render('SfynxCmfBundle:Frontend:contact.html.twig', array(
                'form' => $form->createView()
        ));
    }            
}
