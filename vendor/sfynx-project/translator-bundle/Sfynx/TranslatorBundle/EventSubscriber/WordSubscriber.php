<?php
/**
 * This file is part of the <Translator> project.
 *
 * @subpackage Translator
 * @package    EventSubscriber 
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TranslatorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\CoreBundle\EventListener\abstractListener;
use Sfynx\TranslatorBundle\Entity\Word;

/**
 * Position Subscriber.
 *
 * @subpackage Translator
 * @package    EventSubscriber 
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WordSubscriber  extends abstractListener implements EventSubscriber
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;    
    
    /**
     * Initialization of subscriber
     * 
     * @param string $encryptorClass  The encryptor class.  This can be empty if
     * a service is being provided.
     * @param string $secretKey The secret key.
     * @param EncryptorInterface|NULL $service (Optional)  An EncryptorInterface.
     * This allows for the use of dependency injection for the encrypters.
     */
    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
    	$this->container = $container;
    }
        
    /**
     * @return array
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postUpdate,
            Events::postRemove,
            Events::postPersist,
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
    
    /**
     * @param EventArgs $args
     * 
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function postUpdate(EventArgs $eventArgs)
    {
        $this->deleteCacheTranslationFiles($eventArgs);
    }
    
    /**
     * @param EventArgs $args
     * 
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function postRemove(EventArgs $eventArgs)
    {
        $this->deleteCacheTranslationFiles($eventArgs);
    }
    
    /**
     * @param EventArgs $args
     * 
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function postPersist(EventArgs $eventArgs)
    {
        $this->deleteCacheTranslationFiles($eventArgs);
    }
    
    /**
     * Sets the specific sortOrders.
     *
     * @return void
     * @access private
     * @author etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    private function deleteCacheTranslationFiles(EventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();        
        if ($entity instanceof Word) {
            $this->container->get("sfynx.translator.wordsloader")->deleteCacheTranslationFiles();
        }
    }      
}
