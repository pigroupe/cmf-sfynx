<?php

/**
 * This file is part of the <Admin> project.
 *
 * @category   Bundle
 * @package    PiApp
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-07-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle;

/**
 * Contains all events thrown in the SFYNX
 */
final class PiAppAdminEvents
{
    /**
     * The HANDLER_LOGIN_CHANGEREDIRECTION event occurs when the user connection is successful.
     *
     * This event allows you to modify the default values of the url redirection after a connection user.
     * The event listener method receives a BootStrap\TranslationBundle\Route\RouteTranslatorFactory instance.
     */
    const HANDLER_LOGIN_CHANGEREDIRECTION = 'pi.login.changeredirection';

    /**
     * The HANDLER_LOGIN_CHANGEREDIRECTION event occurs when the user connection is successful.
     *
     * This event allows you to modify the default values of the url redirection after a connection user.
     * The event listener method receives a BootStrap\TranslationBundle\Route\RouteTranslatorFactory instance.
     */
    const HANDLER_LOGIN_CHANGERESPONSE = 'pi.login.changeresponse';    
}
