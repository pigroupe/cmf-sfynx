<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Trigger
 * @package    Event
 * @subpackage Object
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
namespace Sfynx\TriggerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Common\EventArgs;

/**
 * Response event of connection user.
 *
 * @category   Trigger
 * @package    Event
 * @subpackage Object
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 */
class TriggerEvent extends Event
{
    /**
     * @var EventArgs $eventArgs
     */
    private $eventArgs;
    
    /**
     * @var Object $entities
     */
    private $entities;
    
    /**
     * @var array $options
     */
    private $options;    
   
    
    public function __construct($eventArgs)
    {
        $this->eventArgs  = $eventArgs;
    }
    
    /**
     * @return eventArgs
     */
    public function geteventArgs()
    {
    	return $this->eventArgs;
    }
    
    /**
     * @return locale
     */
    public function getOptions()
    {
    	return $this->options;
    }
    
    /**
     * @return  void
     */
    public function setOptions($option, $status)
    {
    	$this->options[$status][] = $option;
    }   
    
    /**
     * @return redirect
     */
    public function getEntities()
    {
    	return $this->entities;
    }
    
    /**
     * @return  void
     */
    public function setEntities($entity, $status = "persist")
    {
    	$this->entities[$status][] = $entity;
    }   
    
    /**
     * @return object entity
     */
    public function getEntity()
    {
    	return $this->eventArgs->getEntity();
    }   
    
    /**
     * @return object Manager
     */
    public function getEntityManager()
    {
    	return $this->eventArgs->getEntityManager();
    }      
    
    /**
     * @return object UnitOfWork Manager
     */
    public function getUnitOfWork()
    {
    	return $this->getEntityManager()->getUnitOfWork();
    }       
}
