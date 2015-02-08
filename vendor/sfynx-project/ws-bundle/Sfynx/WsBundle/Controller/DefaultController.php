<?php
/**
 * This file is part of the <WS> project.
 *
 * @subpackage   WS
 * @package    Controller
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\Controller;

use Sfynx\WsBundle\Exception\ClientException;
use Sfynx\AuthBundle\Controller\abstractController;
use Sfynx\ToolBundle\Util\PiStringManager;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * This controller is made for define all webservices.
 * 
 * @subpackage WS
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DefaultController extends abstractController
{
    /**
     * Template : test
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @Route("/ws/test", name="ws_request_test")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
    	return $this->render('SfynxWsBundle:Default:index.html.twig');
    }
        
    /**
     * Check the demand management of authentication permission.
     * 
     * <code>
     *  /ws/auth/get/permisssion?ws_user_id=hg%2C%2C&ws_application=vGGt&ws_key=0A1TG4GO&ws_format=json
     * </code>
     *  
     * @Route("/ws/auth/get/permisssion", name="ws_auth_getpermission")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAuthPermisssionAction()
    {
    	$em      = $this->getDoctrine()->getManager();
        $request = $this->container->get('request');
    	//
    	if (!$request->headers->has('x-auth-ws_key') 
                || !$request->headers->has('x-auth-ws_format') 
                || !$request->headers->has('x-auth-ws_user_id') 
                || !$request->headers->has('x-auth-ws_application')
        ) {
            //-----we initialize de logger-----
            $logger = $this->container->get('sfynx.tool.log_manager');
            $logger->setInit('log_client_auth', date("YmdH"));
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [BEGIN SET BAD VALIDATE TOKEN AUTH REQUEST]");
            //-----we set errors in the logger-----
            $logger->setErr(date("Y-m-d H:i:s") . " [LOG] problem : missing parameter");
            $logger->setErr(date("Y-m-d H:i:s") . " [LOG] url :" . $request->getUri());
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [END]");
            //-----we save in the file log-----
            $env = $this->container->get("kernel")->getEnvironment();
            $config = $this->container->getParameter("ws.auth");
    	    if (isset($config['log'][$env])) {
                $is_debug = $config['log'][$env];
                if ($is_debug){
                        $logger->save();
                }   		
            }
    	    throw ClientException::callBadAuthRequest(__CLASS__);
    	}
    	$key            = $request->headers->get('x-auth-ws_key', '');
    	$format         = $request->headers->get('x-auth-ws_format', 'json');
    	$userId         = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($request->headers->get('x-auth-ws_user_id', null), $key);
    	$application    = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($request->headers->get('x-auth-ws_application', null), $key);    	
    	// we check if the user ID exists in the authentication service.
    	// If the user ID doesn't exist, we generate.
    	if (!$this->isUserdIdExisted($userId)) {
            //-----we initialize de logger-----
            $logger = $this->container->get('sfynx.tool.log_manager');
            $logger->setInit('log_client_auth', date("YmdH"));
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [BEGIN SET BAD VALIDATE TOKEN AUTH REQUEST]");
            //-----we set errors in the logger-----
            $logger->setErr(date("Y-m-d H:i:s") . " [LOG] problem : userID '".$userId."' does not existed in the database.");
            $logger->setErr(date("Y-m-d H:i:s") . " [LOG] url :" . $request->getUri());
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [END]");
            //-----we save in the file log-----
            $env = $this->container->get("kernel")->getEnvironment();
            $config = $this->container->getParameter("ws.auth");
            if (isset($config['log'][$env])) {
                $is_debug = $config['log'][$env];
                if ($is_debug){
                    $logger->save();
                }   		
            }
    	    throw ClientException::callBadAuthRequest(__CLASS__);
    	} else {
    	    // else we get the token associated to the user ID.
    	    $token = $this->getTokenByUserIdAndApplication($userId, $application);
    	    if ($token) {
    	        $isAuthorization = true;
    	    } else {
    	    	$now = new \Datetime();
    	        $token            = md5($now->getTimestamp()) . strtoupper(PiStringManager::random(24));
    	        $isAuthorization  = false;
    	    }
    	    //-----we initialize de logger-----
    	    $logger = $this->container->get('sfynx.tool.log_manager');
    	    $logger->setInit('log_client_auth', date("YmdH"));
    	    //-----we set info in the logger-----
    	    $logger->setInfo(date("Y-m-d H:i:s") . " [BEGIN GET AUTH PERMISSION AUTH REQUEST]");
    	    //-----we set errors in the logger-----
    	    $logger->setInfo(date("Y-m-d H:i:s") . " [URL] " . $request->getUri());
    	    //-----we set info in the logger-----
    	    $logger->setInfo(date("Y-m-d H:i:s") . " [END]");
    	    //-----we save in the file log-----
            $env = $this->container->get("kernel")->getEnvironment();
            $config = $this->container->getParameter("ws.auth");
            if (isset($config['log'][$env])) {
                $is_debug = $config['log'][$env];
                if ($is_debug){
                        $logger->save();
                }   		
            }  	    
    	}    	
    	if ($format == 'json') {
            $tab                  = array();
            $tab['authorization'] = $isAuthorization;
            $tab['token']         = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($token, $key);
            //
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');            
    	    //-----we initialize de logger-----
    	    $logger = $this->container->get('sfynx.tool.log_manager');
    	    $logger->setInit('log_client_auth_result', date("YmdH"));
    	    //-----we set info in the logger-----
    	    $logger->setInfo(date("Y-m-d H:i:s") . " [BEGIN GET AUTH PERMISSION AUTH REQUEST RESULT]");
    	    //-----we set errors in the logger-----
    	    $logger->setInfo(date("Y-m-d H:i:s") . " [RESULT] " . json_encode($tab));
    	    //-----we set info in the logger-----
    	    $logger->setInfo(date("Y-m-d H:i:s") . " [END]");
    	    //-----we save in the file log-----
            $env = $this->container->get("kernel")->getEnvironment();
            $config = $this->container->getParameter("ws.auth");
            if (isset($config['log'][$env])) {
                $is_debug = $config['log'][$env];
                if ($is_debug){
                        $logger->save();
                }   		
            }             
    	}
    	
    	return $response;    	
    }
    
    /**
     * Check the demand management of authentication permission.
     *
     * <code>
     *  /ws/auth/validate/token?ws_user_id=hmA,&ws_application=vGGt&ws_key=0A1TG4GO&ws_format=json&ws_token=lWeMZ6x5go6jg3V7pqFtnZByiYKrl2yK
     * </code>
     * 
     * @Route("/ws/auth/validate/token", name="ws_auth_validatetoken")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function validateTokenAction()
    {
    	$em      = $this->getDoctrine()->getManager();
        $request = $this->container->get('request');
    	//
        if (!$request->headers->has('x-auth-ws_key') 
                || !$request->headers->has('x-auth-ws_format') 
                || !$request->headers->has('x-auth-ws_user_id')
                || !$request->headers->has('x-auth-ws_token') 
                || !$request->headers->has('x-auth-ws_application')
        ) {
            //-----we initialize de logger-----
            $logger = $this->container->get('sfynx.tool.log_manager');
            $logger->setInit('log_client_auth', date("YmdH"));
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [BEGIN SET BAD VALIDATE TOKEN AUTH REQUEST]");
            //-----we set errors in the logger-----
            $logger->setErr(date("Y-m-d H:i:s") . " [LOG] url :" . $request->getUri());
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [END]");
            //-----we save in the file log-----
            $env = $this->container->get("kernel")->getEnvironment();
            $config = $this->container->getParameter("ws.auth");
            if (isset($config['log'][$env])) {
                $is_debug = $config['log'][$env];
                if ($is_debug){
                        $logger->save();
                }   		
            }
    	    throw ClientException::callBadAuthRequest(__CLASS__);
    	}  
    	$key     = $request->headers->get('x-auth-ws_key', '');
    	$format  = $request->headers->get('x-auth-ws_format', 'json');
    	$userId  = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($request->headers->get('x-auth-ws_user_id', null), $key);
    	$token   = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($request->headers->get('x-auth-ws_token', null), $key);
    	$application    = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($request->headers->get('x-auth-ws_application', null), $key);    	
    	// If the user ID exists,
    	// we associate the token to the userId
    	if ($this->isUserdIdExisted($userId)) {
            $success = $this->setAssociationUserIdWithApplicationToken($userId, $token, $application);
    	} else {
            $success = false;
    	}
    	//-----we initialize de logger-----
    	$logger = $this->container->get('sfynx.tool.log_manager');
    	$logger->setInit('log_client_auth', date("YmdH"));
    	//-----we set info in the logger-----
    	$logger->setInfo(date("Y-m-d H:i:s") . " [BEGIN SET VALIDATE TOKEN AUTH REQUEST]");
    	//-----we set errors in the logger-----
    	$logger->setInfo(date("Y-m-d H:i:s") . " [URL] " . $request->getUri());
    	//-----we set info in the logger-----
    	$logger->setInfo(date("Y-m-d H:i:s") . " [END]");
    	//-----we save in the file log-----
    	$env = $this->container->get("kernel")->getEnvironment();
    	$config = $this->container->getParameter("ws.auth");
    	if (isset($config['log'][$env])) {
	    	$is_debug = $config['log'][$env];
	    	if ($is_debug){
	    		$logger->save();
	    	}   		
    	}
        //    	 
    	if ( $success && ($format == 'json') ) {
    	    $tab= array();
    	    $tab['access_token'] = true;
    
            $response = new Response(json_encode($tab)); 
    	    $response->headers->set('Content-Type', 'application/json');
    	}
    	 
    	return $response;
    }    
    
    /**
     * We connect user by his id value.
     *
     * <code>
     *  /ws/auth/authenticate/id?ws_user_id=hmA,&ws_key=0A1TG4GO
     * </code>
     *
     * @Route("/ws/auth/authenticate/id", name="ws_auth_authenticate_user_by_id")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function authenticateByIdAction()
    {
    	$em          = $this->getDoctrine()->getManager();
    	$request     = $this->container->get('request');
    	$userManager = $this->container->get('fos_user.user_manager');
    	//
    	if ($request->headers->get('x-auth-ws_key') == '') {
            throw new \Exception('x-auth-ws_key not specified');
    	}
    	if ($request->headers->get('x-auth-ws_user_id') == '') {
            throw new \Exception('x-auth-ws_user_id not specified');
    	}
        //
    	$key         = $request->headers->get('x-auth-ws_key', '');
    	$referer_url = $request->headers->get('x-auth-ws_redirect_uri', '');
    	$userId      = $this->container->get('sfynx.tool.twig.extension.tool')->decryptFilter($request->headers->get('x-auth-ws_user_id', null), $key);
    	//
    	if (!empty($referer_url)) {
            $response = new RedirectResponse($referer_url);
    	} else {
            $response = new Response();
    	}
    	//
    	$entity   = $em->getRepository('SfynxUserBundle:User')->find($userId);
    	if ($entity instanceof \Sfynx\AuthBundle\Entity\User) {
            return $this->authenticateUser($entity, $response, false);
    	} else {
            $tab = array();
            $tab['succes_authenticate'] = false;
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
    	}
    }  
    
    /**
     * We get user info and redirect to new domaine to connect user.
     *
     * <code>
     *  if (
     *       (!$this->request->cookies->has('ws-sfynx-sso-connected'))
     *   ) {  
     *       // we get sso parameters values
     *       $sso_prefix_host      = $this->_container()->getParameter("ws.sso.prefix_host");
     *       $sso_uri_proxy        = $this->_container()->getParameter("ws.sso.uri_proxy");
     *       $sso_application_name = $this->_container()->getParameter("ws.sso.application_name");
     *       // we create the uri proxy
     *       $redirectUri       = $this->_container()->get('request')->getUri();   
     *       $redirectHost      = $sso_prefix_host.$this->_container()->get('request')->getHttpHost();   
     *       $redirectUriProxy  = $sso_uri_proxy . $this->_container()->get('router')->generate('ws_auth_authenticate_proxy');
     *       $redirectUriProxy .= sprintf('?ws_redirect_uri=%s', rawurlencode($redirectUri));
     *       $redirectUriProxy .= sprintf('&ws_redirect_host=%s', rawurlencode($redirectHost));
     *       $redirectUriProxy .= sprintf('&ws_application=%s', $sso_application_name);
     *       // we set response
     *       $response = new RedirectResponse($redirectUriProxy, 301);
     *       $event->setResponse($response);
     *       
     *       return;
     *   }
     * </code> 
     * 
     * @Route("/ws/auth/authenticate/proxy", name="ws_auth_authenticate_proxy")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function authenticateProxyAction()
    {
    	$em          = $this->getDoctrine()->getManager();
    	$request     = $this->container->get('request');
    	$userManager = $this->container->get('fos_user.user_manager');
    	//
    	if ($request->get('ws_redirect_uri') == '') {
    	    throw new \Exception('ws_key not specified');
    	}
    	if ($request->get('ws_redirect_host') == '') {
        	throw new \Exception('ws_application not specified');
    	}
    	if ($request->get('ws_application') == '') {
    	    throw new \Exception('ws_application not specified');
    	}
    	//
        $referer_url  = $request->get('ws_redirect_uri', '');
    	$referer_host = $request->get('ws_redirect_host', '');
    	$application  = $request->get('ws_application', null);
    	if ($this->get('security.context')->getToken() 
                instanceof UsernamePasswordToken) {
            // we get user
            $token = $this->getTokenByUserIdAndApplication(
                $this->get('security.context')->getToken()->getUser(),
                $application
            );
            // we set url redirection
            $redirectUri  = rawurldecode($referer_host) .
            $this->container->get('router')->generate('ws_auth_authenticate_user_by_token');
            $redirectUri .= sprintf('?ws_redirect_uri=%s', rawurldecode($referer_url));
            $redirectUri .= sprintf('&ws_application=%s', $application);
            $redirectUri .= sprintf('&ws_token=%s', $token);
            $redirectUri .= sprintf('&ws_locale=%s', $locale);
            // --------------------
            // SSO TEST
            // --------------------  
            $this->container->get('logger')->info("[LOG-SSO-2: authenticateProxyAction]: authentication");
            // --------------------
            // END SSO TEST
            // --------------------        
        } else {
            $redirectUri  = rawurldecode($referer_host) .
            $this->container->get('router')->generate('ws_auth_authenticate_user_by_token');
            $redirectUri .= sprintf('?ws_redirect_uri=%s', rawurldecode($referer_url));
            // --------------------
            // SSO TEST
            // --------------------  
            $this->container->get('logger')->info("[LOG-SSO-2: authenticateProxyAction] : no authentication");
            // --------------------
            // END SSO TEST
            // --------------------      
        }
        $response = new RedirectResponse($redirectUri);

    	return $response;
     }    

    /**
     * We connect user by his id value.
     *
     * <code>
     *  /ws/auth/authenticate/token?ws_application=vGGt&ws_key=0A1TG4GO&&ws_token=lWeMZ6x5go6jg3V7pqFtnZByiYKrl2yK
     * </code>
     *
     * @Route("/ws/auth/authenticate/token", name="ws_auth_authenticate_user_by_token")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function authenticateByTokenAction()
    {
    	$em          = $this->getDoctrine()->getManager();
        $request     = $this->container->get('request');
        $userManager = $this->container->get('fos_user.user_manager');
        //
        if (($request->get('ws_token') == '')
            && ($request->get('ws_redirect_uri') != '')
        ) {
            $response = new RedirectResponse($request->get('ws_redirect_uri'));
            // we do not remove the cookie due to avoid a rollback from SSO connexion in HandlerRequest
            $response->headers->setCookie(
                new Cookie(
                    'findmrmiles-connected',
                    0,
                    time() + 8640000
                )
            );
            // --------------------
            // SSO TEST
            // --------------------   
            $this->container->get('logger')->info("[LOG-SSO-3: authenticateByTokenAction] no authentication : url redirection : ".$request->get('ws_redirect_uri'));
            // --------------------
            // END SSO TEST
            // --------------------             

            return $response;
        }
        if ($request->get('ws_token') == '') {
            throw new \Exception('ws_application not specified');
        }
        if ($request->get('ws_application') == '') {
            throw new \Exception('ws_application not specified');
        }
        //
        $referer_url = $request->get('ws_redirect_uri', '');
        $token       = $request->get('ws_token', null);
        $application = $request->get('ws_application', null);
        $locale      = $request->get('ws_locale', null);
        //
        if (!empty($referer_url)) {
            $response = new RedirectResponse($referer_url);
            // --------------------
            // SSO TEST
            // -------------------- 
            $this->container->get('logger')->info("[LOG-SSO-3: authenticateByTokenAction] authentication : url redirection : ".$referer_url);
            // --------------------
            // END SSO TEST
            // --------------------              
        } else {
            $response = new Response();
        }
        // we set the locale country value egal to the locale .com value
        $response->headers->setCookie(new Cookie('_locale', $locale, time() + 8640000));
        // we get user by token
        $user = $this->getUserByTokenAndApplication($token, $application);
        if ($user instanceof \FindMrMiles\UserBundle\Entity\User) {
            return $this->authenticateUser($user, $response, false);
        } else {
            $response = new RedirectResponse($request->get('ws_redirect_uri'));
            // we do not remove the cookie due to avoid a rollback from SSO connexion in HandlerRequest
            $response->headers->setCookie(
                new \Symfony\Component\HttpFoundation\Cookie(
                    'findmrmiles-connected',
                    0,
                    time() + 8640000
                )
            );            

            return $response;
        }
    }    
    
    /**
     * Check the result request of a url.
     *
     * @Route("/ws/auth/ajax/get", name="ws_auth_ajax")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function _ajax_getAuthAction()
    {
    	$request = $this->container->get('request');
    	$em = $this->getDoctrine()->getManager();
    	$result = array();
        //
    	$handler     = $request->get('handler', '');
    	$getParams   = $request->get('getParams', null);
    	// we set the ws request
   	$result = $this->container->get('sfynx.ws.client.auth')->getPermission($handler, $getParams);
    	// we throw an exception if ws return false
    	$get_http_response_code = intval(substr($result['header'][0], 9, 3));
    	if ($get_http_response_code != 200){
            //-----we initialize de logger-----
            $logger = $this->container->get('sfynx.tool.log_manager');
            $logger->setInit('log_client_auth_bad_request', date("YmdH"));
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [BEGIN BAD AUTH REQUEST]");
            //-----we set errors in the logger-----
            $loggerr->setErr(date("Y-m-d H:i:s") . " [LOG] WS url :" . $request->getUri());
            $logger->setErr(date("Y-m-d H:i:s") . " [LOG] param url :" . $result['url']);
            //-----we set info in the logger-----
            $logger->setInfo(date("Y-m-d H:i:s") . " [END]");
            //-----we save in the file log-----
            $logger->save();
            throw ClientException::callBadAuthRequest(__CLASS__);
    	} else {
            // we hide the value of the url
            if (isset($result['url'])) {
                unset($result['url']);
            }
            if (isset($result['header'])) {
                unset($result['header']);
            }
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
    	}
    }    
}
