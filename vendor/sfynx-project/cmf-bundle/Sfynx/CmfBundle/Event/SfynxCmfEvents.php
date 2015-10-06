<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   Cmf
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
namespace Sfynx\CmfBundle\Event;

/**
 * Contains all events thrown in the SFYNX
 * 
 * @category   Cmf
 * @package    Event
 * @subpackage Constant
 * @final
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * 
 */
final class SfynxCmfEvents
{
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
    
    /**
     * The REGISTRATION_COMPLETED event occurs when the user is correctly created
     * 
     */    
    const REGISTRATION_INITIALIZE = 'pi.user.registration.initialize'; 
    
    /**
     * The REGISTRATION_SUCCESS event occurs when the user is correctly created
     * 
     */    
    const REGISTRATION_SUCCESS = 'pi.user.registration.success';
    
    /**
     * The REGISTRATION_SUCCESS event occurs when the user is correctly created
     * 
     */    
    const REGISTRATION_WS_SUCCESS = 'pi.user.registration.ws.success';    
    
    /**
     * The REGISTRATION_COMPLETED event occurs when the user is correctly created
     * 
     */    
    const REGISTRATION_COMPLETED = 'pi.user.registration.completed';    
}
