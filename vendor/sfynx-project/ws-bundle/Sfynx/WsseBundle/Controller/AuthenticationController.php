<?php
/**
 * This file is part of the <Ws-se> project.
 *
 * @category   Ws-se
 * @package    Controller
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
namespace Sfynx\WsseBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sfynx\CoreBundle\Controller\abstractController;
use Sfynx\AuthBundle\Entity\User;
use Sfynx\AuthBundle\Model\UserWS;
use Sfynx\AuthBundle\Event\ResponseEvent;
use Sfynx\AuthBundle\Event\SfynxAuthEvents;

/**
 * Authentication Controller
 * 
 * @category   Ws-se
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 * @Route("/api/wsse/v1")
 */
class AuthenticationController extends abstractController
{
    /**
     * For WSSE Header generation, use http://www.teria.com/~koseki/tools/wssegen/ . 
     * Simple, efficient. (auto nonce, auto date. No before wsse. User = username in database, password = encypted salt+password = password as in database)
     * 
     * Install and open Rest Console for chrome. Fields to use :
     * Request API : Your server address, with an API service (http://obtao.localhost/app_dev.php/api/me for us)
     * Custom Header (+) :
     *      - Parameter = “x-wsse“,
     *      - Value 
     *              Host: localhost
     *              X-WSSE: UsernameToken Username="admin", PasswordDigest="GtGn8TZX/KVlEQuerkESVElc64g=", Nonce="NzViZGU3NjM5MTAzZmU1Nw==", Created="2015-02-18T17:09:12Z"
     *              Authorization: Basic YWRtaW46YWRtaW4=
     * Authorization Hearder :  Authorization profile=”UsernameToken”
     * 
     * @link http://www.teria.com/~koseki/tools/wssegen/
     * @Route("/user/authentication", name="wsse_users_authentication")
     * @Method("GET")
     */
    public function authenticationAction(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $user = $this->container->get('security.context')->getToken()->getUser();
        $locale = $this->container->get("request")->getLocale();

        if ($user instanceof User) {
            $response->setContent($this->getUserInformation($user));
            $response->setStatusCode(200);
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
            $event_response = new ResponseEvent(null, $dateExpire, $this->getRequest(), $user, $locale);
            $this->container->get('event_dispatcher')->dispatch(SfynxAuthEvents::HANDLER_LOGIN_CHANGERESPONSE, $event_response);
            //
            foreach ($event_response->getResponse()->headers->getCookies() as $cokkie) {
                $response->headers->setCookie($cokkie);
            }
        }

        return $response;
    }

    private function getUserInformation(User $user)
    {
        $userWs = new UserWS($user);

        return $userWs->jsonSerialize();
    }
}
