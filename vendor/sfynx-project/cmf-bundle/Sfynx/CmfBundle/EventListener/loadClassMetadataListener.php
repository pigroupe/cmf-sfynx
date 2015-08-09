<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Eventlistener
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\CmfBundle\EventListener\CoreListener;

/**
 * Custom post load entities listener.
 * The loadClassMetadata event occurs after the mapping metadata for a class has been loaded
 * from a mapping source (annotations/xml/yaml).
 * 
 * @subpackage   Admin_Eventlistener
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class loadClassMetadataListener extends CoreListener
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
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
//        exemple :
//         $classMetadata = $eventArgs->getClassMetadata();
//         $fieldMapping = array(
//             'fieldName' => 'about',
//             'type' => 'string',
//             'length' => 255
//         );
//         $classMetadata->mapField($fieldMapping);        
    }
}