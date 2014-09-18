<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace Tms\Bundle\MediaClientBundle\StorageProvider;

use Tms\Bundle\MediaClientBundle\Model\Media;

abstract class AbstractStorageProvider implements StorageProviderInterface
{
    protected $name;
 
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function add(Media & $media)
    {
        if (null === $media->getUploadedFile() || !$media->getUploadedFile()->getPathName()) {
            return false;
        }

        // Update case
        if ($media->getProviderReference()) {
            // Remove the previous associated media
            $this->remove($media);
        }

        // Set the provider name
        $media->setProviderName($this->getName());

        return $this->doAdd($media);
    }

    /**
     * Do add a media
     *
     * @param  Media $media
     * @return boolean
     */
    abstract public function doAdd(Media & $media);

    /**
     * {@inheritdoc}
     */
    abstract public function remove($reference);

    /**
     * {@inheritdoc}
     */
    abstract public function getMediaPublicUrl($reference);
}
