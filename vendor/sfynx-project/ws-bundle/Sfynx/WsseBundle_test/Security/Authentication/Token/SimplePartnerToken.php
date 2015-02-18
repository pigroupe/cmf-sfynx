<?php

namespace Sfynx\WsseBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class SimplePartnerToken extends AbstractToken
{
    public $simpleToken;

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // Si l'utilisateur a des rôles, on le considère comme authentifié
        //http://symfony.com/fr/doc/current/cookbook/security/custom_authentication_provider.html
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return 'WS_WRITER';
    }
}
