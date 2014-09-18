<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\Media;

use Tms\Bundle\MediaBundle\Entity\Media;

class ResponseMedia
{
    protected $content;
    protected $contentType;
    protected $contentLength;
    protected $eTag;
    protected $lastModifiedAt;
    protected $expires;
    protected $maxAge;
    protected $sharedMaxAge;

    /**
     * Set content
     *
     * @param string $content
     * @return ResponseMedia
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     * @return ResponseMedia
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set contentLength
     *
     * @param integer $contentLength
     * @return ResponseMedia
     */
    public function setContentLength($contentLength)
    {
        $this->contentLength = $contentLength;

        return $this;
    }

    /**
     * Get contentLength
     *
     * @return integer
     */
    public function getContentLength()
    {
        return $this->contentLength;
    }

    /**
     * Set eTag
     *
     * @param string $eTag
     * @return ResponseMedia
     */
    public function setETag($eTag)
    {
        $this->eTag = $eTag;

        return $this;
    }

    /**
     * Get eTag
     *
     * @return string
     */
    public function getETag()
    {
        return $this->eTag;
    }

    /**
     * Set lastModifiedAt
     *
     * @param DateTime $lastModifiedAt
     * @return ResponseMedia
     */
    public function setLastModifiedAt(\DateTime $lastModifiedAt)
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    /**
     * Get lastModifiedAt
     *
     * @return DateTime
     */
    public function getLastModifiedAt()
    {
        return $this->lastModifiedAt;
    }

    /**
     * Set expires
     *
     * @param DateTime $expires
     * @return ResponseMedia
     */
    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set maxAge
     *
     * @param integer $maxAge
     * @return ResponseMedia
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;

        return $this;
    }

    /**
     * Get maxAge
     *
     * @return integer
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * Set sharedMaxAge
     *
     * @param integer $sharedMaxAge
     * @return ResponseMedia
     */
    public function setSharedMaxAge($sharedMaxAge)
    {
        $this->sharedMaxAge = $sharedMaxAge;

        return $this;
    }

    /**
     * Get sharedMaxAge
     *
     * @param integer
     */
    public function getSharedMaxAge()
    {
        return $this->sharedMaxAge;
    }
}
