<?php
/**
 * This file is part of the <Auth> project.
 *
 * @subpackage   Auth
 * @package    Controller
 * @abstract
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-10-01
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Model\UserInterface;

/**
 * abstract controller.
 *
 * @subpackage Auth
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiMailerManager extends Controller
{
    const SESSION_EMAIL = 'fos_user_send_resetting_email/email';
     
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
   
    /**
     * Send mail to reset user password
     * 
     * @param UserInterface $user
     * @param string        $route_reset_connexion
     * @param string        $body_type             ['body_text', 'body_html']
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */  
    public function sendConfirmationEmailMessage(UserInterface $user, $route_reset_connexion = 'fos_user_registration_confirm', $body_type = "body_html")
    {
        $url      = $this->container->get('sfynx.tool.route.factory')->getRoute($route_reset_connexion, array('token' => $user->getConfirmationToken()));
        $html_url = 'http://'.$this->container->get('request')->getHttpHost() . $this->container->get('request')->getBasePath().$url;
        $html_url = "<a href='$html_url'>" . $html_url . "</a>";
        $templateFile = str_replace('::', ':', $this->container->getParameter('sfynx.auth.theme.login')).'Registration:email.txt.twig';
        $from     = $this->container->getParameter('sfynx.auth.theme.email.registration.from_email.address');
        //
        $this->sendEmailMessage($templateFile, $from, $user, $html_url, $body_type);
    }    
    
    /**
     * Send mail to reset user password
     * 
     * @param UserInterface $user
     * @param string        $route_reset_connexion
     * @param string        $body_type             ['body_text', 'body_html']
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function sendResettingEmailMessage(UserInterface $user, $route_reset_connexion = 'fos_user_resetting_reset', $body_type = "body_html")
    {
        $url      = $this->container->get('sfynx.tool.route.factory')->getRoute($route_reset_connexion, array('token' => $user->getConfirmationToken()));
        $html_url = 'http://'.$this->container->get('request')->getHttpHost() . $this->container->get('request')->getBasePath().$url;
        $html_url = "<a href='$html_url'>" . $html_url . "</a>";
        $templateFile = str_replace('::', ':', $this->container->getParameter('sfynx.auth.theme.login')).'Resetting:email.txt.twig';
        $from     = $this->container->getParameter('sfynx.auth.theme.email.resetting.from_email.address');
        //
        $this->sendEmailMessage($templateFile, $from, $user, $html_url, $body_type);
    }   
    
    /**
     * @param string        $templateFile 
     * @param string        $from
     * @param UserInterface $user
     * @param string        $html_url
     * @param string        $body_type    ['body_text', 'body_html']
     */
    protected function sendEmailMessage($templateFile, $from, $user, $html_url, $body_type = "body_html")
    {
        $templateContent = $this->container->get('twig')->loadTemplate($templateFile);
        $email_subject   = ($templateContent->hasBlock("subject")
                ? $templateContent->renderBlock("subject", array(
                    'user'            => $user,
                    'confirmationUrl' => $html_url,
                ))
                : "Default subject here");
        $email_body      = ($templateContent->hasBlock("body")
                ? $templateContent->renderBlock($body_type, array(
                    'user'            => $user,
                    'confirmationUrl' => $html_url,
                ))
                : "Default body here");     
        $this->container->get("sfynx.tool.mailer_manager")->send(
            $from, 
            $user->getEmail(), 
            $email_subject, 
            $email_body
        );
    }    
    
    /**
     * Generate link to reset user password (return link with url)
     * 
     * @param UserInterface $user
     * @param string        $route_reset_connexion
     * @param string        $title
     * @param array         $parameters
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function sendResettingEmailMessageLink(UserInterface $user, $route_reset_connexion, $title = '', $parameters = array())
    {  
    	$tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());
        $em = $this->container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        
        $this->container->get('request')
                ->getSession()
                ->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
        
        $parameters = array_merge($parameters, array('token' => $user->getConfirmationToken()));
        
        $url      = $this->container->get('sfynx.tool.route.factory')
                ->getRoute($route_reset_connexion, $parameters);
        $html_url = 'http://'.$this->container->get('request')->getHttpHost() . $this->container->get('request')->getBasePath().$url;
        
        if (empty($title)) {
            $title = $html_url;
        }        
        $result = "<a href='$html_url'>" . $title . "</a>";
        
        return $result;
    }  
    
    /**
     * Send mail to reset user password (return URL)
     * 
     * @param UserInterface $user
     * @param string        $route_reset_connexion
     * @param string        $title
     * @param array         $parameters
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function sendResettingEmailMessageURL(UserInterface $user, $route_reset_connexion, $parameters = array())
    {
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());
        $em = $this->container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $this->container->get('request')
                ->getSession()
                ->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));

        $parameters = array_merge($parameters, array('token' => $user->getConfirmationToken()));

        $url      = $this->container->get('sfynx.tool.route.factory')->getRoute($route_reset_connexion, $parameters);
        $html_url = 'http://'.$this->container->get('request')->getHttpHost() . $this->container->get('request')->getBasePath().$url;

        return $html_url;
    }    
    
    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param UserInterface $user
     *
     * @return string
     */
    public function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }
    
        return $email;
    } 
}
