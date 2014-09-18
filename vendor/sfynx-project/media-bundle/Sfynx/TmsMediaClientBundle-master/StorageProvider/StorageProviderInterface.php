<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace Tms\Bundle\MediaClientBundle\StorageProvider;

use Tms\Bundle\MediaClientBundle\Model\Media;

interface StorageProviderInterface
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Add a media
     *
     * @param  Media $media
     * @return boolean
     */
    public function add(Media & $media);

    /**
     * Remove a media
     *
     * @param  string $reference
     * @return boolean
     */
    public function remove($reference);

    /**
     * Get the media public url
     *
     * @param  string $reference
     * @return string | false
     */
    public function getMediaPublicUrl($reference);
}
