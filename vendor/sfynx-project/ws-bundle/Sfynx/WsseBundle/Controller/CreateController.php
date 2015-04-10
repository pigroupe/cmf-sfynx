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
use Sfynx\CoreBundle\Controller\abstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
class CreateController extends abstractController
{
    /**
     * @Route("/user/create", name="wsse_users_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $handler = $this->get('sfynx.auth.ws.submit_user.handler');
        $response = new Response();
        try {
            $handler->bindDatas($request);
            $createdUserDatas = $handler->process();
            $response->setContent($createdUserDatas);
            $response->setStatusCode(201);
        } catch (\Exception $exception) {
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getCode());
        }
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }    
}
