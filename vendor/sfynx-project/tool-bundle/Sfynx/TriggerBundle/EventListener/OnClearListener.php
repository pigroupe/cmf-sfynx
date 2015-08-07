<?php
/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage postLoad
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

use Doctrine\ORM\Event\OnClearEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\TriggerBundle\EventListener\abstractTriggerListener;
use Sfynx\TriggerBundle\Event\TriggerEvents;
use Sfynx\TriggerBundle\Event\ViewObject\TriggerEvent;

/**
 * Custom on clear entities listener.
 * The onClear event occurs when the EntityManager#clear() operation is invoked,
 * after all references to entities have been removed from the unit of work.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage onClear
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 */
class OnClearListener extends abstractTriggerListener
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
     * Methos which will be called when the event is thrown.
     *
     *
     * @param OnClearEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function onClear(OnClearEventArgs $eventArgs)
    {
        $entityClass = $eventArgs->getEntityClass();
        $object_event = new TriggerEvent($eventArgs, $this->container, $entityClass);
        
        $this->container
                ->get('event_dispatcher')
                ->dispatch(TriggerEvents::TRIGGER_EVENT_ONCLEAR, $object_event); 
    }    
}
