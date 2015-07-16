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

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\TriggerBundle\EventListener\abstractListener;
use Sfynx\TriggerBundle\SfynxTriggerEvents;
use Sfynx\TriggerBundle\Event\TriggerEvent;

/**
 * Custom post load entities listener.
 * The postLoad event occurs for an entity after the entity has been loaded into the 
 * current EntityManager from the database or after the refresh operation has been applied to it.
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
 */
class PostLoadListener extends abstractListener
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
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->container->get('event_dispatcher')->dispatch(SfynxTriggerEvents::TRIGGER_EVENT_POSTLOAD, new TriggerEvent($eventArgs, $this->container)); 
    }    
}
