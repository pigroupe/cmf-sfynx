<?php

namespace Sfynx\WsseBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Sfynx\WsseBundle\Security\Authentication\Token\SimplePartnerToken;

class SimplePartnerListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        print_r(md5('tokenForTest' . date('Y-m-d')));exit;
        $request = $event->getRequest();
        
        if (!$request->headers->has('x-auth-token')) {
            $response = new Response();
            $response->setStatusCode(401);
            $event->setResponse($response);

            return;
        }
        $token = new SimplePartnerToken();
        $token->setUser('wspartner');
        $token->simpleToken   = $request->headers->get('x-auth-token');
        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);
        } catch (AuthenticationException $failed) {
            // Deny authentication with a '401 Unauthorized' HTTP response
            $response = new Response();
            $response->setStatusCode(401);            
            $event->setResponse($response);
        }
    }
}
