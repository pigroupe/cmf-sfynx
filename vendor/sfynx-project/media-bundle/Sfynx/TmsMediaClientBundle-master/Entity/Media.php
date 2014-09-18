<?php

/**
 * @author Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 */

namespace Tms\Bundle\MediaClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tms\Bundle\MediaClientBundle\Model\Media as BaseMedia;

/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 * @ORM\HasLifecycleCallbacks()
 */
class Media extends BaseMedia
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="public_uri", type="string")
     */
    protected $publicUri;

    /**
     * @ORM\Column(name="mime_type", type="string")
     */
    protected $mimeType;

    /**
     * @ORM\Column(name="provider_name", type="string")
     */
    protected $providerName;

    /**
     * @ORM\Column(name="provider_reference", type="string")
     */
    protected $providerReference;

    /**
     * @ORM\Column(name="provider_data", type="json_array", nullable=true)
     */
    protected $providerData;

    /**
     * @ORM\Column(type="string")
     */
    protected $extension;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * onCreate
     *
     * @ORM\PrePersist()
     */
    public function onCreate()
    {
        $date = new \DateTime('now');
        $this->setCreatedAt($date);
        $this->setUpdatedAt($date);
    }

    /**
     * onUpdate
     *
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $date = new \DateTime('now');
        $this->setUpdatedAt($date);
    }
}
