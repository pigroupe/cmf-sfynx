<?php
/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    EventListener
 * @subpackage postGenerateSchema
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

use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\TriggerBundle\EventListener\abstractTriggerListener;
use Sfynx\TriggerBundle\Event\TriggerEvents;
use Sfynx\TriggerBundle\Event\ViewObject\TriggerEvent;

/**
 * Custom post load entities listener.
 * The loadClassMetadata event occurs after the mapping metadata for a class has been loaded
 * from a mapping source (annotations/xml/yaml).
 * 
 * @category   Trigger
 * @package    EventListener
 * @subpackage postGenerateSchema
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 */
class SchemaListener extends abstractTriggerListener
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
     * @param \Doctrine\ORM\Event\GenerateSchemaEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function postGenerateSchema(GenerateSchemaEventArgs $eventArgs)
    {
        $schema = $eventArgs->getSchema();
        $object_event = new TriggerEvent($eventArgs, $this->container, $schema);
        
        $this->container
                ->get('event_dispatcher')
                ->dispatch(TriggerEvents::TRIGGER_EVENT_POSTGENERATESCHEM, $object_event); 
    }    
}
