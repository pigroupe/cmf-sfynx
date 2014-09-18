<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 *
 * @ORM\Table(name="media",
 *    indexes={
 *        @ORM\Index(name="media_source", columns={"source"}),
 *        @ORM\Index(name="media_mimetype", columns={"mime_type"})
 *    },
 *    uniqueConstraints={@ORM\UniqueConstraint(name="media_reference", columns={"reference"})}
 * )
 * @ORM\Entity(repositoryClass="Tms\Bundle\MediaBundle\Entity\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Media
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $reference;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $extension;

    /**
     * @var string
     * @ORM\Column(name="provider_service_name", type="string")
     */
    protected $providerServiceName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $size;

    /**
     * @var string
     * @ORM\Column(name="mime_type", type="string", length=255)
     */
    protected $mimeType;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $raw;

    /**
     * On create
     *
     * @ORM\PrePersist()
     */
    public function onCreate()
    {
        $now = new \DateTime();
        $this
            ->setCreatedAt($now)
        ;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setEnabled(true);
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'                  => $this->getId(),
            'source'              => $this->getSource(),
            'reference'           => $this->getReference(),
            'extension'           => $this->getExtension(),
            'providerServiceName' => $this->getProviderServiceName(),
            'name'                => $this->getName(),
            'description'         => $this->getDescription(),
            'size'                => $this->getSize(),
            'mimeType'            => $this->getMimeType(),
            'enabled'             => $this->getEnabled(),
            'createdAt'           => $this->getCreatedAt()->format('c'),
            'metadata'            => $this->getMetadata(),
        );
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Media
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set reference
     *
     * @param string $reference
     * @return Media
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Media
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set providerServiceName
     *
     * @param string $providerServiceName
     * @return Media
     */
    public function setProviderServiceName($providerServiceName)
    {
        $this->providerServiceName = $providerServiceName;

        return $this;
    }

    /**
     * Get providerServiceName
     *
     * @return string
     */
    public function getProviderServiceName()
    {
        return $this->providerServiceName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Media
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Media
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Media
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Media
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Media
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Media
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set metadata
     *
     * @param array $metadata
     * @return Media
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @param string $key
     * @return array
     */
    public function getMetadata($key = null)
    {
        if(null === $key) {
            return $this->metadata;
        }

        return (isset($this->metadata[$key]) ? $this->metadata[$key] : null);
    }

    /**
     * Set raw
     *
     * @param string $raw
     * @return Media
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Get raw
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }
}
