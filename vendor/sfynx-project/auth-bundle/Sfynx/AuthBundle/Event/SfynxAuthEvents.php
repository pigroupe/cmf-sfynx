<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
 * @package    Event
 * @subpackage Constant
 * @final
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
namespace Sfynx\AuthBundle\Event;

/**
 * Contains all events thrown in the SFYNX
 * 
 * @category   Auth
 * @package    Event
 * @subpackage Constant
 * @final
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * 
 */
final class SfynxAuthEvents
{
    /**
     * The HANDLER_LOGIN_FAILURE event occurs when the connection is failure.
     *
     * This event allows you to modify the default values of the url redirection after a connection user.
     * The event listener method receives a Sfynx\ToolBundle\Route\RouteTranslatorFactory instance.
     */
    const HANDLER_LOGIN_FAILURE = 'pi.handler.login.failure';
    
    /**
     * The HANDLER_LOGIN_CHANGEREDIRECTION event occurs when the user connection is successful.
     *
     * This event allows you to modify the default values of the url redirection after a connection user.
     * The event listener method receives a Sfynx\ToolBundle\Route\RouteTranslatorFactory instance.
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
 }
