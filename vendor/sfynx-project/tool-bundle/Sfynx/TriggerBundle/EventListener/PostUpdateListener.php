<?php
/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage postUpdate
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
 * Custom post update entities listener.
 * The postUpdate event occurs after the database update operations to entity data.
 * It is not called for a DQL UPDATE statement.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage postUpdate
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 */
class PostUpdateListener extends abstractListener
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
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->container->get('event_dispatcher')->dispatch(SfynxTriggerEvents::TRIGGER_EVENT_PREUPDATE, new TriggerEvent($eventArgs, $this->container)); 
    }    
}
