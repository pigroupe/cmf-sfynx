<?php
/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage onFlush
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
namespace Sfynx\SfynxTrigger\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\TriggerBundle\EventListener\abstractListener;
use Sfynx\TriggerBundle\SfynxTriggerEvents;
use Sfynx\TriggerBundle\Event\TriggerEvent;

/**
 * Custom post load entities listener.
 * The onFlush event occurs after the change-sets of all managed entities are computed.
 * This event is not a lifecycle callback.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage onFlush
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 */
class OnFlushListener extends abstractListener
{
    /**
     * Constructs a new instance of SecurityListener.
     *
     * @param ContainerInterface        $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
        
    /**
     * OnFlush is a very powerful event. It is called inside EntityManager#flush() after 
     * the changes to all the managed entities and their associations have been computed.
     * This means, the onFlush event has access to the sets of:
     *      Entities scheduled for insert
     *        Entities scheduled for update
     *        Entities scheduled for removal
     *        Collections scheduled for update
     *        Collections scheduled for removal
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $this->container->get('event_dispatcher')->dispatch(SfynxTriggerEvents::TRIGGER_EVENT_ONFLUSH, new TriggerEvent($eventArgs)); 
    }    
}
