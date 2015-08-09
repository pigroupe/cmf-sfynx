<?php
/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage preRemove
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

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\TriggerBundle\EventListener\abstractTriggerListener;
use Sfynx\TriggerBundle\Event\TriggerEvents;
use Sfynx\TriggerBundle\Event\ViewObject\TriggerEvent;

/**
 * Custom pre remove entities listener.
 * The preRemove event occurs for a given entity before the respective EntityManager 
 * remove operation for that entity is executed.
 * It is not called for a DQL DELETE statement.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage preRemove
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 */
class PreRemoveListener extends abstractTriggerListener
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
     * Method which will be called when the event is thrown.
     *
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function PreRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $object_event = new TriggerEvent($eventArgs, $this->container, $entity);
        
        $this->container
                ->get('event_dispatcher')
                ->dispatch(TriggerEvents::TRIGGER_EVENT_PREREMOVE, $object_event); 
    }    
}
