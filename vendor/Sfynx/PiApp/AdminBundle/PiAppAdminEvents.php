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
     * The HANDLER_LOGIN_FAILURE event occurs when the connection is failure.
     *
     * This event allows you to modify the default values of the url redirection after a connection user.
     * The event listener method receives a BootStrap\TranslationBundle\Route\RouteTranslatorFactory instance.
     */
    const HANDLER_LOGIN_FAILURE = 'pi.handler.login.failure';
    
    /**
     * The HANDLER_LOGIN_CHANGEREDIRECTION event occurs when the user connection is successful.
     *
     * This event allows you to modify the default values of the url redirection after a connection user.
     * The event listener method receives a BootStrap\TranslationBundle\Route\RouteTranslatorFactory instance.
     */
    const HANDLER_LOGIN_CHANGEREDIRECTION = 'pi.handler.login.changeredirection';

    /**
     * The HANDLER_LOGIN_CHANGERESPONSE event occurs when the user connection is successful.
     *
     * This event allows you to modify the default values of the response after a user connection .
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_LOGIN_CHANGERESPONSE = 'pi.handler.login.changeresponse';    
    
    /**
     * The HANDLER_LOGOUT_CHANGERESPONSE event occurs when the user deconnection is successful.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_LOGOUT_CHANGERESPONSE = 'pi.handler.logout.changeresponse';    
    
    /**
     * The HANDLER_REQUEST_CHANGERESPONSE_PREFIX_LOCALE_REDIRECTION event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_REQUEST_CHANGERESPONSE_PREFIX_LOCALE_REDIRECTION = 'pi.handler.request.prefixlocale.changeresponse';   

    /**
     * The HANDLER_REQUEST_CHANGERESPONSE_SEO_REDIRECTION event occurs when the url is in the SEO_link file for 301 redirection .
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_REQUEST_CHANGERESPONSE_SEO_REDIRECTION = 'pi.handler.request.seo.changeresponse';    
    
    /**
     * The HANDLER_REQUEST_CHANGERESPONSE_NOSCOPE event occurs when the user is not in the scop configure in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const HANDLER_REQUEST_CHANGERESPONSE_NOSCOPE = 'pi.handler.request.noscope.changeresponse';    
}
