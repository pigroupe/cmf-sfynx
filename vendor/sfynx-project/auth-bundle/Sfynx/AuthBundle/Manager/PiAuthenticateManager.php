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
namespace Sfynx\AuthBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;

use Sfynx\AuthBundle\Event\ResponseEvent;
use Sfynx\AuthBundle\SfynxAuthEvents;
use Sfynx\AuthBundle\Entity\User;

/**
 * abstract controller.
 *
 * @subpackage Auth
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiAuthenticateManager extends Controller
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
     * Authenticate a user with Symfony Security
     *
     * @param UserInterface $user
     * @param Response      $response
     * 
     * @return void
     * @access public
     */
    public function authenticateDefaultUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->loginUser(
                    $this->container->getParameter('fos_user.firewall_name'),
                    $user,
                    $response);
        } catch (\Symfony\Component\Security\Core\Exception\AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }      
    
    /**
     * Authenticate a user with Symfony Security.
     *
     * @param UserInterface $user
     * @param null|Response $response
     * @param boolean       $deleteToken
     * 
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function authenticateUser(UserInterface $user = null, &$response = null, $deleteToken = false)
    {
    	$em          = $this->container->get('doctrine')->getManager();
        $locale      = $this->container->get('request')->getLocale();
    	$request     = $this->container->get('request');
        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $userManager = $this->container->get('fos_user.user_manager');
        // set user
        if (is_null($user)) {
            $token   = $request->query->get('token');
            $user    = $userManager->findUserByConfirmationToken($token);
        }
        //
        $token       = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->container->get('security.context')->setToken($token); //now the user is logged in
        $request->getSession()->set('_security_'.$providerKey, serialize($token));
        $request->getSession()->set('_security_secured_area', serialize($token));
	// we delete token user
        if ($deleteToken) {
            $user->setConfirmationToken(null);
            $userManager->updateUser($user);
            $em->persist($user);
            $em->flush();	                
        }
        //
        if ($response instanceof Response) {
            // Record all cookies in relation with ws.
            $dateExpire     = $this->container->getParameter('sfynx.core.cookies.date_expire');
            $date_interval  = $this->container->getParameter('sfynx.core.cookies.date_interval');
            // Record the layout variable in cookies.
            if ($dateExpire && !empty($date_interval)) {
                if (is_numeric($date_interval)) {
                    $dateExpire = time() + intVal($date_interval);
                } else {
                    $dateExpire = new \DateTime("NOW");
                    $dateExpire->add(new \DateInterval($date_interval));
                }
            } else {
                $dateExpire = 0;
            }
            // we apply all events allowed to change the redirection response
            $event_response = new ResponseEvent($response, $dateExpire, $this->getRequest(), $this->getUser(), $locale);
            $this->container->get('event_dispatcher')->dispatch(SfynxAuthEvents::HANDLER_LOGIN_CHANGERESPONSE, $event_response);
            $response = $event_response->getResponse();
        }  

        return $response;
    }   
    
    /**
     * Disconnect a user with Symfony Security.
     *
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disconnectUser()
    {
    	$this->container->get('request')->getSession()->invalidate();
    }   
    
    /**
     * Return the token object.
     *
     * @return UsernamePasswordToken
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getToken()
    {
        return  $this->container->get('security.context')->getToken();
    }  
    
    /**
     * Return the token object.
     * 
     * @param UserInterface $user
     * 
     * @return UsernamePasswordToken
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function tokenUser(UserInterface $user)
    {
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());
        $em = $this->container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        
        $this->container->get('request')
                ->getSession()
                ->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
         
        return $user->getConfirmationToken();
    }    
    
    /**
     * Send mail to reset user password (return link with url)
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
    public function sendResettingEmailMessage(UserInterface $user, $route_reset_connexion, $title = '', $parameters = array())
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

        $url       = $this->container->get('sfynx.tool.route.factory')->getRoute($route_reset_connexion, $parameters);
        $html_url = 'http://'.$this->container->get('request')->getHttpHost() . $this->container->get('request')->getBasePath().$url;

        return $html_url;
    }    

    /**
     * Return the connected user entity.
     *
     * @return string User name
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUser()
    {
        return $this->getToken()->getUser()->getUsername();
    }
    
    /**
     * Return the connected user name.
     *
     * @return string User name
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserName()
    {
        return $this->getToken()->getUser()->getUsername();
    }
    
    /**
     * Return the user permissions.
     *
     * @return array User permissions
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserPermissions()
    {
        return $this->getToken()->getUser()->getPermissions();
    }
    
    /**
     * Return the user roles.
     *
     * @return array    user roles
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserRoles()
    {
        return $this->getToken()->getUser()->getRoles();
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

    /**
     * Return if yes or no the user is anonymous token.
     *
     * @return boolean
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isAnonymousToken()
    {
        if (
            ($this->getToken() instanceof AnonymousToken)
            ||
            ($this->getToken() === null)
        ) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Return if yes or no the user is UsernamePassword token.
     *
     * @return boolean
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isUsernamePasswordToken()
    {
        if ($this->getToken() instanceof UsernamePasswordToken) {
            return true;
        } else {
            return false;
        }
    }    
    
    /**
     * we check if the user ID exists in the authentication service.
     *
     * @param integer $userId
     * 
     * @return boolean
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isUserdIdExisted($userId)
    {
        $em = $this->container->get('doctrine')->getManager();
        $entity = $em->getRepository('SfynxAuthBundle:User')->find($userId);
        if ($entity instanceof User) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * we return the user enity associated to the user token and the application.
     *
     * @param string $token
     * @param string $application
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserByTokenAndApplication($token, $application)
    {
    	$em    = $this->container->get('doctrine')->getManager();
    	$like_app = array(strtoupper($application.'::'.$token));
        $like = serialize($like_app);
    	$query = $em->getRepository('SfynxAuthBundle:User')
            ->createQueryBuilder('a')
            ->select('a')
            ->andWhere("a.applicationTokens = '{$like}'")
            ->getQuery();  
        // ATTENTION avec a.applicationTokens LIKE "%..%" empeche l'utilisation de 'index sur la recherceh par la valeur de application_token
        // Avec un site à très fort traffic, cela explose alors la bdd si pas d'index sur application token.
        // create cache tag of the query
        $input_hash = (string) (sha1(serialize($query->getParameters()) . $query->getSQL()));
        $query->useResultCache(true, 84600, $input_hash); 
        $query->useQueryCache(true); 
        //
        $user = $query->getOneOrNullResult();
    	
    	return $user;
    }    
    
    /**
     * we return the user enity associated to the user token and the application.
     *
     * @param string $token
     * @param string $application
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getUserByTokenAndApplicationMultiple($token, $application)
    {
    	$em    = $this->container->get('doctrine')->getManager();
    	$like  = strtoupper($application.'::'.$token);
    	$query = $em->getRepository('SfynxAuthBundle:User')
            ->createQueryBuilder('a')
            ->select('a')
            ->andWhere("a.applicationTokens LIKE '%{$like}%'")
            ->getQuery();  
        // ATTENTION avec a.applicationTokens LIKE "%..%" empeche l'utilisation de 'index sur la recherceh par la valeur de application_token
        // Avec un site à très fort traffic, cela explose alors la bdd si pas d'index sur application token.
        // create cache tag of the query
        $input_hash = (string) (sha1(serialize($query->getParameters()) . $query->getSQL()));
        $query->useResultCache(true, 84600, $input_hash); 
        $query->useQueryCache(true); 
        //
        $user = $query->getOneOrNullResult();
    	
    	return $user;
    }       

    /**
     * we return the token associated to the user ID.
     * 
     * @param integer $userId
     * @param string  $application
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getTokenByUserIdAndApplication($userId, $application)
    {
    	$em = $this->container->get('doctrine')->getManager();
    	if ($userId instanceof User) {
            $entity = $userId;
    	} else {
            $entity = $em->getRepository('SfynxAuthBundle:User')->find($userId);
    	}
        if ($entity instanceof User) {
            return $entity->getTokenByApplicationName($application);
        }
        
        return false;
    }

    /**
     * we associate the token to the userId.
     * 
     * @param integer $userId
     * @param string  $token
     * @param string  $application
     * 
     * @return boolean
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function setAssociationUserIdWithApplicationToken($userId, $token, $application)
    {
    	$em = $this->container->get('doctrine')->getManager();
        if ($userId instanceof User) {
            $entity = $userId;
        } else {
            $entity = $em->getRepository('SfynxAuthBundle:User')->find($userId);
        }
        if ($entity instanceof User) {
            $entity->addTokenByApplicationName($application, $token);
            $em->persist($entity);
            $em->flush();
            
            return true;
        } else {
            return false;
        }
    }    
}
