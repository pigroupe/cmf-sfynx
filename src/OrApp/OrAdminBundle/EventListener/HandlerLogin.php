<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OrApp\OrAdminBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PiApp\AdminBundle\EventListener\HandlerLogin as baseLoginHandler;


/**
 * Custom login handler.
 * This allow you to execute code right after the user succefully logs in.
 * 
 * @category   Admin_Eventlistener
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class HandlerLogin extends baseLoginHandler
{
    /**
     * Constructs a new instance of SecurityListener.
     * 
     * @param SecurityContext $security The security context
     * @param EventDispatcher $dispatcher The event dispatcher
     * @param Doctrine        $doctrine
     * @param Container        $container
     */
    public function __construct(SecurityContext $security, EventDispatcher $dispatcher, Doctrine $doctrine, ContainerInterface $container)
    {
        parent::__construct($security, $dispatcher, $doctrine, $container);
    }
}