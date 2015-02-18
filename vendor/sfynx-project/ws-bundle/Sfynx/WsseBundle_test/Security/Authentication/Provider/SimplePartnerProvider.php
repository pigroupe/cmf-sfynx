<?php

namespace Sfynx\WsseBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Sfynx\WsseBundle\Security\Authentication\Token\SimplePartnerToken;

class SimplePartnerProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct(UserProviderInterface $userProvider, $secretKey)
    {
        $this->userProvider = $userProvider;
        $this->secretKey = $secretKey;
    }

    public function authenticate(TokenInterface $token)
    {
        if ($this->validateSimpleToken($token->simpleToken)) {
            $authenticatedToken = new SimplePartnerToken();

            return $authenticatedToken;
        }

        throw new AuthenticationException('The authentication failed.');
    }

    protected function validateSimpleToken($simpleToken)
    {
        $md5Generate = md5($this->secretKey . date('Y-m-d'));

        return $simpleToken === $md5Generate;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof SimplePartnerToken;
    }
}
