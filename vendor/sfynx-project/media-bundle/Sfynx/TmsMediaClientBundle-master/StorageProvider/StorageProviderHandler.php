<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace Tms\Bundle\MediaClientBundle\StorageProvider;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Tms\Bundle\MediaClientBundle\Model\Media;

class StorageProviderHandler implements EventSubscriber
{
    protected $storageProviders = array();

    /**
     * Get StorageProvider
     *
     * @param string $serviceName
     * @return StorageProviderInterface|null
     */
    public function getStorageProvider($serviceName)
    {
        return isset($this->storageProviders[$serviceName]) ?
            $this->storageProviders[$serviceName] :
            null
        ;
    }

    /**
     * Add StorageProvider
     *
     * @param StorageProviderInterface $provider
     * @param string $serviceName
     */
    public function addStorageProvider(StorageProviderInterface $provider, $serviceName)
    {
        $this->storageProviders[$serviceName] = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'preRemove'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Media) {
            $provider = $this->getStorageProvider($entity->getProviderName());
            $provider->add($entity);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Media) {
            $provider = $this->getStorageProvider($entity->getProviderName());
            $provider->add($entity);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Media && $entity->isSynchronizedAction(Media::REMOVE_ACTION)) {
            $provider = $this->getStorageProvider($entity->getProviderName());
            $provider->remove($entity->getProviderReference());
        }
    }
}
