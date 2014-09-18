<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Controller;

use Sfynx\AuthBundle\Controller\abstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sfynx\CmfBundle\Entity\Enquiry;
use Sfynx\CmfBundle\Form\EnquiryType;
use Sfynx\CmfBundle\Entity\Page as Page;
use Sfynx\CmfBundle\Entity\TranslationPage;

use Sfynx\AuthBundle\Event\ResponseEvent;
use Sfynx\AuthBundle\SfynxAuthEvents;

/**
 * Frontend controller.
 *
 * @category   Auth
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class FrontendController extends abstractController
{
    /**
     * Main default page
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-24
     */
    public function indexAction()
    {
   	    return $this->render($this->container->getParameter('sfynx.auth.theme.layout.admin.home'), array());
    }    
    
    /**
     * Licence page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-24
     */
    public function licenceAction()
    {
    	return $this->render('SfynxAuthBundle:Frontend:licence.html.twig', array());
    }    
        
    /**
     * Configures the local language
     *
     * @param string $langue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
      * @since 2011-12-29
     */    
    public function setLocalAction($langue = '')
    {
        // It tries to redirect to the original page.
        $new_url = $this->container->get('sfynx.tool.route.factory')->getRefererRoute($langue, null, true);
        $response = new RedirectResponse($new_url, 302);
        // we get params
        $this->date_expire    = $this->container->getParameter('sfynx.core.cookies.date_expire');
        $this->date_interval  = $this->container->getParameter('sfynx.core.cookies.date_interval');        
        // Record the layout variable in cookies.
        if ($this->date_expire && !empty($this->date_interval)) {
            if (is_numeric($this->date_interval)) {
                $dateExpire = time() + intVal($this->date_interval);
            } else {
                $dateExpire = new \DateTime("NOW");
                $dateExpire->add(new \DateInterval($this->date_interval));
            }
        } else {
        	$dateExpire = 0;
        }
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('_locale', $langue, $dateExpire));
        
        return $response;
    }    
    
    /**
     * Redirection function
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-24
     */
    public function redirectionuserAction()
    {
    	if ($this->getRequest()->cookies->has('sfynx-redirection')) {
    		$parameters   = array();
    		$redirection  = $this->getRequest()->cookies->get('sfynx-redirection');
    		$response     = new RedirectResponse($this->container->get('sfynx.tool.route.factory')->getRoute($redirection, $parameters));
    	} else {
    		$response     = new RedirectResponse($this->container->get('sfynx.tool.route.factory')->getRoute('home_page'));
    	}
    	 
    	return $response;
    }  

    /**
     * Login failure function
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2014-07-26
     */
    public function loginfailureAction()
    {
        if ( $this->getRequest()->isXmlHttpRequest() ) {
        	$response = new Response(json_encode('error'));
        	$response->headers->set('Content-Type', 'application/json');
        } else {
            // we create the redirection request.
            $response     = new RedirectResponse($this->container->get('sfynx.tool.route.factory')->getRoute('fos_user_security_login'));
       		// we apply all events allowed to change the redirection response
       		$event_response = new ResponseEvent($response);
       		$this->container->get('event_dispatcher')->dispatch(SfynxAuthEvents::HANDLER_LOGIN_FAILURE, $event_response);
       		$response = $event_response->getResponse();
        }
    
    	return $response;
    }    
        
}
