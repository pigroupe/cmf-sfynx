<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category Gedmo_EventSubscriber
 * @package  EventSubscriber
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since    2012-10-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\TriggerBundle\EventListener\abstractTriggerListener;

/**
 * Bundle Subscriber.
 *
 * @category Gedmo_EventSubscriber
 * @package  EventSubscriber
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EventSubscriberBundle  extends abstractTriggerListener implements EventSubscriber
{
    /**
     * Constructor
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        
        if (class_exists('Sfynx\MediaBundle\Entity\Mediatheque')) {
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'contact1',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Contact',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media'
            ));  
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'contact2',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Contact',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media1'
            ));  
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'menu',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Menu',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media'
            ));  
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'slider',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Slider',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media'
            ));     
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'block',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Block',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media'
            ));  
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'block2',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Block',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media1'
            ));  
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'organigram',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Organigram',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media'
            )); 
            $this->addAssociation('Sfynx\MediaBundle\Entity\Mediatheque', 'mapOneToMany', array(
                'fieldName'     => 'entitycategory',
                'targetEntity'  => 'PiApp\GedmoBundle\Entity\Category',
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => 'media'
            ));             
        }         
    }
    
    /**
     * @return array
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata
        );
    }

    /**
     * @param EventArgs $args
     * 
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function recomputeSingleEntityChangeSet(EventArgs $args)
    {
        $em = $args->getEntityManager();

        $em->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $em->getClassMetadata(get_class($args->getEntity())),
            $args->getEntity()
        );
    }
}
