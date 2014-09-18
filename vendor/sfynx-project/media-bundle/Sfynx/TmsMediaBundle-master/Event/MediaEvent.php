<?php

namespace Tms\Bundle\MediaBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tms\Bundle\MediaBundle\Entity\Media;

/**
 * MediaEvent
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
class MediaEvent extends Event
{
    protected $media;

    /**
     * Constructor
     *
     * @param Media $media
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Get Object
     *
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}