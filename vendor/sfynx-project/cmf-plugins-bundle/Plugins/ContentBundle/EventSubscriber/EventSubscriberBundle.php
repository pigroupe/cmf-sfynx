<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category   Gedmo_EventSubscriber
 * @package    EventSubscriber
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-10-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plugins\ContentBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\CoreBundle\EventListener\abstractListener;

/**
 * Bundle Subscriber.
 *
 * @category   Gedmo_EventSubscriber
 * @package    EventSubscriber
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EventSubscriberBundle  extends abstractListener implements EventSubscriber
{
    /**
     * @return array
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postUpdate,
            Events::postRemove,
            Events::postPersist,
        );
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     * 
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
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function postUpdate(EventArgs $eventArgs)
    {
       	$this->articleFlux($eventArgs);
    }
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function postRemove(EventArgs $eventArgs)
    {
    	$this->articleFlux($eventArgs);
    }
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function postPersist(EventArgs $eventArgs)
    {
    	$this->articleFlux($eventArgs);
    }
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
    }
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function preRemove(EventArgs $eventArgs)
    {
    }
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function prePersist(EventArgs $eventArgs)
    {
    }    
    
    private function articleFlux(EventArgs $eventArgs)
    {
    	$entity			= $eventArgs->getEntity();
    	$entityManager 	= $eventArgs->getEntityManager();
    	$locale         = $this->_container()->get('request')->getLocale();
    	//
    	if (
    		($entity instanceof \Plugins\ContentBundle\Entity\Article)
    		||
    		($entity instanceof \Plugins\ContentBundle\Entity\BlocGeneral)
    	) {
	    	$flux 				= $this->_container()->get('doctrine')->getRepository('PluginsContentBundle:Article')->findFlux();    
	    	$tab  				= array();
	    	$tab['references']  = array();
	    	//
	    	foreach ($flux as $key=>$zone) {
	    		// we refrseh in the locale language
	    		$zone->setTranslatableLocale($locale);
	    		$entityManager->refresh($zone);
	    		//
	    		if ($key==0) {
	    			$item 	= array();
	    			$item['type'] 		= "logo";
	    			$item['name'] 		= "Marie de Broissia Header";
	    			$item['date'] 		= "";
	    			$item['dimension']  = "simple";
	    			$item['classname']  = array();
	    			$item['content'] 	= "<h1><a href='http://www.mariedebroissia.fr/' target='_blank' alt='mariedebroissia.fr'><img src='/ressources/photos/img_logo_mariedebroissia.jpg' alt='Marie de Broissia' /><span class='tnoir'>L'observatoire</span><span class='tblanc'>Communication Managériale</span></a></h1>";
	    			array_push($item['classname'], "main-header");
	    			array_push($tab['references'], $item);
	    		}
	    		$key=$key+1;
				//    
	    		$item = array();
	    		$title = $zone->getBlocgeneral()->getTitle();
	    		if ($zone->getType() == 'image' || $zone->getType() == 'grande_image') {
	    			$item['type']  = "photo";
	    		} elseif ($zone->getType() == 'large_image') {
	    			$item['type']  = "infography";
	    		} elseif ($zone->getType() == 'video') {
	    			$item['type']  = "video";
	    		} elseif ($zone->getType() == 'article') {
	    			$item['type']  = "info";
	    			$item['title'] = $title;
	    		}
	    		$item['name']    = $title;
	    		$item['caption'] = $title;
	    		if ($zone->getType()=='grande_image') {
	    			$item['dimension'] = "doublesize";
	    		} elseif ($zone->getType()=='large_image') {
	    			$item['dimension'] = "doublewidth";
	    		} else {
	    			$item['dimension'] = "simple";
	    		}
				//
	    		$item['date'] = date("d/m/Y",$zone->getBlocgeneral()->getPublishedAt()->getTimestamp());
	    		if(!is_null($zone->getBlocgeneral()->getArchiveAt())) {
	    			$item['date_expiration'] = date("d/m/Y",$zone->getBlocgeneral()->getArchiveAt()->getTimestamp());
	    		} else {
	    			$item['date_expiration'] = '';
	    		}
	            if ($zone->getType() != 'article') {
	            	$obj_media = $zone->getBlocgeneral()->getMedia();
	                if ($obj_media) {
	                    try {
	                        //
	                    	//$mediaPath = $this->_container()->get('pi_app_admin.twig.extension.route')->getMediaUrlFunction($obj_media->getImage(), 'reference', true, $obj_media->getUpdatedAt(), 'plugin_content_article_media_reference');
	                        //$src 	   = $this->_container()->get('kernel')->getRootDir() . '/../web/' . $mediaPath;
	                    	//                        
	                        $item['imageUri'] = $this->_container()->get('pi_app_admin.twig.extension.route')->getMediaUrlFunction($obj_media->getImage(), 'default_mosaique', true, $obj_media->getUpdatedAt(), 'plugin_content_article_media_default_mosaique');
	                        //
	                        $item['imageBigUri'] = $this->_container()->get('pi_app_admin.twig.extension.route')->getMediaUrlFunction($obj_media->getImage(), 'default_big', true, $obj_media->getUpdatedAt(), 'plugin_content_article_media_default_big');
	                        //
	                    } catch (\Exception $e) {
	                        $item['imageUri']    = '';
	                        $item['imageBigUri'] = '';
	                    }
	                } else {
	                    $item['imageUri']    = '';
	                    $item['imageBigUri'] = '';
	                }    
	            }
	    		$item['description'] = $zone->getBlocgeneral()->getDescriptif() . '<br/><br/>' . $zone->getContent();
	            if ($zone->getType() == 'image' || $zone->getType() == 'grande_image' || $zone->getType() == 'article') {
	                if ($zone->getUrl() == null) {
	                    $item['externalUrl'] = "";
	                    $item['aliasUrl'] 	 = "";
	                } else {
	                    $item['externalUrl'] = $zone->getUrl();
	                    $item['aliasUrl'] 	 = $zone->getAlias();
	                }
	            }
	            if ($zone->getType() == 'video') {
	                $item['videoUri'] = $zone->getUrl();
	            }
	            if ($zone->getPopin() == true) {
	                $item['visite'] = true; 
	            } else {
	                $item['visite'] = false;
	            }
	            array_push($tab['references'], $item);    
	        }
			//    
	        $file = $this->_container()->get('kernel')->getRootDir()."/../web/ressources/references_{$locale}.json";
	        \PiApp\AdminBundle\Util\PiFileManager::save($file, json_encode($tab), 0777, LOCK_EX);
    	}
    } 
    
}