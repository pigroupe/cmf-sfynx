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
namespace Sfynx\TriggerBundle;

/**
 * Contains all events thrown in the SFYNX
 */
final class SfynxTriggerEvents
{
    /**
     * The TRIGGER_EVENT_PREPERSIST event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_PREPERSIST = 'sfynx.trigger.prepserist';   

    /**
     * The TRIGGER_EVENT_POSTPERSIST event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTPERSIST = 'sfynx.trigger.postpserist'; 
    
    /**
     * The TRIGGER_EVENT_PREUPDATE event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_PREUPDATE = 'sfynx.trigger.preupdate';  
    
    /**
     * The TRIGGER_EVENT_POSTUPDATE event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTUPDATE = 'sfynx.trigger.postupdate';     
    
    /**
     * The TRIGGER_EVENT_PREREMOVE event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_PREREMOVE = 'sfynx.trigger.preremove';     
    
    /**
     * The TRIGGER_EVENT_POSTREMOVE event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTREMOVE = 'sfynx.trigger.postremove';   
    
    /**
     * The TRIGGER_EVENT_POSTLOAD event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTLOAD = 'sfynx.trigger.postload';     
    
    /**
     * The TRIGGER_EVENT_ONFLUSH event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_ONFLUSH = 'sfynx.trigger.onflush';    
    
    /**
     * The TRIGGER_EVENT_POSTGENERATESCHEM event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_POSTGENERATESCHEM = 'sfynx.trigger.postGenerateSchema';    
    
    /**
     * The TRIGGER_EVENT_LOADCLASSMETADATA event occurs when the prefixe locale in route has been enabled in config.yml.
     *
     * This event allows you to modify the default values of the response before a user deconnection.
     * The event listener method receives a Symfony\Component\HttpFoundation\Response instance.
     */
    const TRIGGER_EVENT_LOADCLASSMETADATA = 'sfynx.trigger.loadClassMetadata';      
}
