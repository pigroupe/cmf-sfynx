<?php
/**
 * This file is part of the <Ws-se> project.
 *
 * @category   Ws-se
 * @package    Security
 * @subpackage Firewall
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsseBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Sfynx\WsseBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Listener WSSE
 *
 * @category   Ws-se
 * @package    Security
 * @subpackage Firewall
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class WsseListener implements ListenerInterface
{
    /**
     * @var SecurityContextInterface $securityContext
     */
    protected $securityContext;
    
    /**
     * @var AuthenticationManagerInterface $authenticationManager
     */    
    protected $authenticationManager;
    
    /**
     * @var LoggerInterface $logger
     */      
    protected $logger;

    public function __construct(SecurityContextInterface $securityContext,
                               AuthenticationManagerInterface $authenticationManager,
                               LoggerInterface $logger
    ){
        $this->securityContext       = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->logger                = $logger;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        if (!$request->headers->has('x-wsse') 
                || 1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)
        ) {
            // Deny authentication with a '403 Forbidden' HTTP response
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);
            
            return;
        }

        $token = new WsseUserToken();
        $token->setUser($matches[1]);

        $token->digest   = $matches[2];
        $token->nonce    = $matches[3];
        $token->created  = $matches[4];

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            // ... you might log something here
            $failedMessage = 'WSSE Login failed for '.$token->getUsername().'. Why ? '.$failed->getMessage();
            $this->logger->err($failedMessage);

            //To deny the authentication clear the token. This will redirect to the login page.
            //Make sure to only clear your token, not those of other authentication listeners.
            $this->securityContext->setToken(null);
            
            // Deny authentication with a '403 Forbidden' HTTP response
            $response = new Response();
            $response->setStatusCode(403);
            $response->setContent($failedMessage);
            $event->setResponse($response);
            
            return;
        }
    }
}
