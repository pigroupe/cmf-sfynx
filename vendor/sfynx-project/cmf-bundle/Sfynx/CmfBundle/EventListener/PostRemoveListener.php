<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Entity
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\CmfBundle\EventListener\CoreListener;

/**
 * Custom post remove entities listener.
 * The postRemove event occurs for an entity after the entity has been deleted.
 * It will be invoked after the database delete operations.
 * It is not called for a DQL DELETE statement.
 *
 * @subpackage   Entity
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PostRemoveListener extends CoreListener
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
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
    	// We remove json file Etag of Page and Widget.
    	$this->_deleteJsonFileEtag($eventArgs, true);
        
        // we set the remove json page information
        $this->_JsonFilePage($eventArgs, 'remove');        
    	
        // we set the postRemove heritage roles management
        $this->_Heritage_roles($eventArgs);
        
        // we set the postRemove languages management
        $this->_locales_language_json_file($eventArgs);
        
        // we set the postRemove Cache Url Generator management
        $this->_updateCacheUrlGenerator($eventArgs);        
    }
    
}