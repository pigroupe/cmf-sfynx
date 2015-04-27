<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-12-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;

use Sfynx\CmfBundle\Builder\PiAuthenticationManagerInterface;
use Sfynx\CmfBundle\Manager\PiCoreManager;
use Sfynx\CmfBundle\Entity\Widget;

/**
 * Description of the Authentication Widget manager
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiAuthenticationManager extends PiCoreManager implements PiAuthenticationManagerInterface 
{    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * Call the tree render source method.
     *
     * @param string $id
     * @param string $lang
     * @param string $params
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-04-19
     */
    public function renderSource($id, $lang = '', $params = null)
    {
        str_replace('~', '~', $id, $count);
        if ($count == 1) {
            list($entity, $method) = explode('~', $this->_Decode($id));
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }
        if (!is_array($params)) {
            $params = $this->paramsDecode($params);
        } else {
            $this->recursive_map($params);
        }
        if (empty($lang)) {
            $lang   = $this->container->get('request')->getLocale();
        }
        $params['locale'] = $lang;        
        if ($method == "_connexion_default") {
            return $this->defaultConnexion($params);
        } elseif ($method == "_reset_default") {
            return $this->resetConnexion($params);        
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }
    }
    
    /**
     * Return the build tree result of a gedmo tree entity, with class options.
     *
     * @param string    $template
     * @access    public
     * @return string
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function defaultConnexion($params = null)
    {
        $em      = $this->container->get('doctrine')->getManager();        
        $request = $this->container->get('request');
        $session = $request->getSession();
        
    	if (isset($params['template']) && !empty($params['template'])) {
            $template = $params['template'];
        } else { 
            $template = $this->container->getParameter('sfynx.auth.theme.login') . "Security:login.html.twig";
        }
        if (empty($params['locale'])) {
            $params['locale']        = $this->container->get('request')->getLocale();
        }        
        if (isset($params['referer_redirection']) && !empty($params['referer_redirection']) && ($params['referer_redirection'] == "true")) {
            $referer_url = $this->container->get('request')->headers->get('referer');
        } else {
            $referer_url = "";
        }  
        if (isset($params['roles']) && !empty($params['roles'])) {
            $roles = $params['roles'];
        } else {
            $roles = "";
        }           
        
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }
        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        // we register the username in session used in dispatcherLoginFailureResponse
        $session->set('login-username', $lastUsername);
        // we test if the number of attempts allowed connections number with the username have been exceeded.
        if (!empty($lastUsername) && $this->container->has('sfynx.auth.dispatcher.login_failure.change_response')) {
            $key = $this->container->get('sfynx.auth.dispatcher.login_failure.change_response')->getKeyValue();
            if ($key == "stop-client") {
                    $session->set('login-username', '');
                    $session->remove(SecurityContext::LAST_USERNAME);
                if ($request->isXmlHttpRequest()) {
                    $response = new Response(json_encode('error'));
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                } else {
                    $new_url = $this->container->get('router')->generate('fos_user_security_login');
                    $session->getFlashBag()->add('errorform', "you exceeded the number of attempts allowed connections!");

                    return new RedirectResponse($new_url);
                }
            }
        }
        $csrfToken = $this->container->has('form.csrf_provider')
        ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
        : null;
        
        $response         =  $this->container->get('templating')->renderResponse($template, array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
            'referer_url'   => $referer_url,
            'roles'         => $roles,        		
            'route'         => $this->container->get('sfynx.tool.route.factory')->getMatchParamOfRoute('_route', $this->container->get('request')->getLocale())
        ));        
        // we delete all permission flash
        $this->getFlashBag()->get('permission');
        
        return $response->getContent();
    }
    
    /**
     * Reset user password
     * 
     * @param null|array $params
     * 
     * @return Response
     */
    public function resetConnexion($params = null)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
                
        if (isset($params['template']) && !empty($params['template'])) {
        	$template = $params['template'];
        } else {
        	$template = $this->container->getParameter('sfynx.auth.theme.login') . "Resetting:reset_content.html.twig";
        }
        if (isset($params['url_redirection']) && !empty($params['url_redirection'])) {
        	$url_redirection = $params['url_redirection'];
        } elseif(isset($params['path_url_redirection']) && !empty($params['path_url_redirection'])) {
        	$url_redirection = $this->container->get('sfynx.tool.route.factory')
                        ->getRoute($params['path_url_redirection'], array('locale'=> $this->container->get('request')->getLocale()));
        } else {
        	$url_redirection = $this->container->get('router')->generate("home_page");
        }        
        $token       = $this->container->get('request')->query->get('token');
        // if a user is connected, we generate automatically the token if it is not given in parameter.
        if (empty($token) 
                && $this->container->get('sfynx.auth.user_manager')->isUsernamePasswordToken()
        ) {
            $token = $this->container
                    ->get('sfynx.auth.user_manager')
                    ->tokenUser($this->container->get('sfynx.auth.user_manager')->getToken()->getUser());
            $user  = $this->container
                    ->get('sfynx.auth.user_manager')
                    ->getToken()->getUser();
        } else {
            $user     = $userManager->findUserByConfirmationToken($token);
        }
        if (null === $user) {
            header('Location: '. $url_redirection);
            exit;
        }
        $form = $formFactory->createForm();
        $form->setData($user);
        if ('POST' === $this->container->get('request')->getMethod()) {
            $form->bind($this->container->get('request'));
            if ($form->isValid()) {
                $userManager->updateUser($user);
                $flash = $this->container->get('translator')->trans('pi.session.flash.resetting.success');
                $this->container->get('request')->getSession()->getFlashBag()->add('success', $flash);
                header('Location: '. $url_redirection);
                exit;
            } else {
                $flash = $this->container->get('translator')->trans('pi.session.flash.resetting.error');
                $this->container->get('request')->getSession()->getFlashBag()->add('error', $flash);
            }
        }        
    	if (isset($params['clearflashes'])) {
            $this->getFlashBag()->clear();
        } else {
            $this->getFlashBag()->get('permission');
        }
		
        return $this->container->get('templating')->renderResponse($template, array(
                'token' => $token,
                'form'  => $form->createView(),
                'route' => $this->container->get('sfynx.tool.route.factory')->getMatchParamOfRoute('_route', $this->container->get('request')->getLocale())
        ))->getContent();
    }    
    
    /**
     * Gets the flash bag.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBag
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getFlashBag()
    {
        return $this->container->get('request')->getSession()->getFlashBag();
    }       
}
