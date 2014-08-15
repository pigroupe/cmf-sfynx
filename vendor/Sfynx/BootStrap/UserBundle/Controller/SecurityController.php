<?php
/**
 * This file is part of the <User> project.
 *
 * @category   BootStrap
 * @package    Controller
 * @since 2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BootStrap\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *
 * @category   BootStrap
 * @package    Controller
 */
class SecurityController extends ContainerAware
{
    public function loginAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();
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
        if (!empty($lastUsername) && $this->container->has('pi_app_admin.dispatcher.login_failure.change_response')) {            
            $key = $this->container->get('pi_app_admin.dispatcher.login_failure.change_response')->getKeyValue();
            if ($key == "stop-client") {
                $session->set('login-username', '');
                $session->remove(SecurityContext::LAST_USERNAME);
                if ($request->isXmlHttpRequest()) {
                    $response = new Response(json_encode('error'));
                    $response->headers->set('Content-Type', 'application/json');
                    
                    return $response;
                } else {
                    $new_url = $this->container->get('router')->generate('home_page');
                    $session->getFlashBag()->add('errorform', "you exceeded the number of attempts allowed connections!");
                    
                    return new RedirectResponse($new_url);
                }
            }
        }
    
        $csrfToken = $this->container->has('form.csrf_provider')
        ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
        : null;

        if ($request->isXmlHttpRequest()) {
        	if ($error){
        		$statut = "error";
        	}
        	else{
        		$statut = "ok";
        	}
        	$response = new Response(json_encode($statut));
        	$response->headers->set('Content-Type', 'application/json');
        	return $response;
        }
                
        return $this->renderLogin(array(
                'last_username' => $lastUsername,
                'error'         => $error,
                'csrf_token' => $csrfToken,
                'NoLayout'    => $this->container->get('request')->query->get('NoLayout')
        ));
    }
    
    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = "PiAppTemplateBundle:Template\\Login\\Security:login.html.twig";
    
        return $this->container->get('templating')->renderResponse($template, $data);
    }    
    
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}