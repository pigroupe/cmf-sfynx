<?php
/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    Event
 * @subpackage const
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
namespace Sfynx\TriggerBundle\Event;

/**
 * Contains all events thrown in the SFYNX
 * 
 * @category   Trigger
 * @package    Event
 * @subpackage const
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
final class TriggerEvents
{
    /**
     * The TRIGGER_EVENT_PREPERSIST event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_PREPERSIST = 'sfynx.trigger.prepserist';   

    /**
     * The TRIGGER_EVENT_POSTPERSIST event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTPERSIST = 'sfynx.trigger.postpserist'; 
    
    /**
     * The TRIGGER_EVENT_PREUPDATE event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_PREUPDATE = 'sfynx.trigger.preupdate';  
    
    /**
     * The TRIGGER_EVENT_POSTUPDATE event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTUPDATE = 'sfynx.trigger.postupdate';     
    
    /**
     * The TRIGGER_EVENT_PREREMOVE event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_PREREMOVE = 'sfynx.trigger.preremove';     
    
    /**
     * The TRIGGER_EVENT_POSTREMOVE event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTREMOVE = 'sfynx.trigger.postremove';   
    
    /**
     * The TRIGGER_EVENT_POSTLOAD event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTLOAD = 'sfynx.trigger.postload';     
    
    /**
     * The TRIGGER_EVENT_ONFLUSH event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_PREFLUSH = 'sfynx.trigger.preflush';    
    
    /**
     * The TRIGGER_EVENT_ONFLUSH event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_ONFLUSH = 'sfynx.trigger.onflush';    
    
    /**
     * The TRIGGER_EVENT_ONFLUSH event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTLUSH = 'sfynx.trigger.postflush';     
    
    /**
     * The TRIGGER_EVENT_POSTGENERATESCHEM event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTGENERATESCHEM = 'sfynx.trigger.postGenerateSchema';    
    
    /**
     * The TRIGGER_EVENT_LOADCLASSMETADATA event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_LOADCLASSMETADATA = 'sfynx.trigger.loadClassMetadata';    
    
    /**
     * The TRIGGER_EVENT_LOADCLASSMETADATA event occurs
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_ONCLEAR = 'sfynx.trigger.onclear';        
}
