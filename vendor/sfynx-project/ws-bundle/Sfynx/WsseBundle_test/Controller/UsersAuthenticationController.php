<?php

namespace Sfynx\WsseBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @package WebserviceBundle
 *
 * @author Alexis Janvier <alexis.janvier@rappfrance.comp>
 */
class UsersAuthenticationController extends Controller
{
    /**
     * @Route("/api/user/authentication", name="wsnbi_users_authentication")
     * @Method("GET")
     *
     */
    public function authenticationAction(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $response->setContent('pouet');
        $response->setStatusCode(200);

        return $response;
    }
}
