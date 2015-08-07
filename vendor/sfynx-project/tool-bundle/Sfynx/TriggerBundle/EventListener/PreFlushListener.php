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
namespace Sfynx\TriggerBundle\EventListener;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\TriggerBundle\EventListener\abstractTriggerListener;
use Sfynx\TriggerBundle\Event\TriggerEvents;
use Sfynx\TriggerBundle\Event\ViewObject\TriggerEvent;

/**
 * Custom post load entities listener.
 * The preFlush event occurs when the EntityManager#flush() operation is invoked,
 * but before any changes to managed entities have been calculated. This event is
 * always raised right after EntityManager#flush() call.
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
class PreFlushListener extends abstractTriggerListener
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
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function preFlush(PreFlushEventArgs  $eventArgs)
    {
        $object_event = new TriggerEvent($eventArgs, $this->container, null);
        
        $this->container
                ->get('event_dispatcher')
                ->dispatch(TriggerEvents::TRIGGER_EVENT_ONFLUSH, $object_event); 
    }    
}
