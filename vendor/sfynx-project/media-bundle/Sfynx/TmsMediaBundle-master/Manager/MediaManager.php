<?php

namespace Tms\Bundle\MediaBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tms\Bundle\MediaBundle\Event\MediaEvent;
use Tms\Bundle\MediaBundle\Event\MediaEvents;
use Tms\Bundle\MediaBundle\StorageMapper\StorageMapperInterface;
use Tms\Bundle\MediaBundle\MetadataExtractor\MetadataExtractorInterface;
use Tms\Bundle\MediaBundle\Media\Transformer\MediaTransformerInterface;
use Tms\Bundle\MediaBundle\Entity\Media;

use Tms\Bundle\MediaBundle\Exception\UndefinedStorageMapperException;
use Tms\Bundle\MediaBundle\Exception\NoMatchedStorageMapperException;
use Tms\Bundle\MediaBundle\Exception\NoMatchedTransformerException;
use Tms\Bundle\MediaBundle\Exception\MediaNotFoundException;
use Tms\Bundle\MediaBundle\Exception\MediaAlreadyExistException;

/**
 * Media manager.
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
class MediaManager extends AbstractManager
{
    protected $configuration;
    protected $storageMappers = array();
    protected $metadataExtractors = array();
    protected $mediaTransformers = array();

    /**
     * Constructor
     *
     * @param EntityManager                 $entityManager
     * @param ContainerAwareEventDispatcher $eventDispatcher
     * @param array                         $configuration
     */
    public function __construct(
        EntityManager $entityManager,
        ContainerAwareEventDispatcher $eventDispatcher,
        $configuration
    )
    {
        parent::__construct($entityManager, $eventDispatcher);
        $this->configuration = $configuration;
    }

    /**
     * Get the default store path
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Get the default store path
     *
     * @return string
     */
    public function getDefaultStorePath()
    {
        return $this->configuration['default_store_path'];
    }

    /**
     * Get the api public endpoint
     *
     * @return string
     */
    public function getApiPublicEndpoint()
    {
        return $this->configuration['api_public_endpoint'];
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return "TmsMediaBundle:Media";
    }

    /**
     * {@inheritdoc}
     */
    public function add($entity)
    {
        $this->getEventDispatcher()->dispatch(
            MediaEvents::PRE_CREATE,
            new MediaEvent($entity)
        );

        parent::add($entity);

        $this->getEventDispatcher()->dispatch(
            MediaEvents::POST_CREATE,
            new MediaEvent($entity)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function update($entity)
    {
        $this->getEventDispatcher()->dispatch(
            MediaEvents::PRE_UPDATE,
            new MediaEvent($entity)
        );

        parent::update($entity);

        $this->getEventDispatcher()->dispatch(
            MediaEvents::POST_UPDATE,
            new MediaEvent($entity)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function delete($entity)
    {
        $this->getEventDispatcher()->dispatch(
            MediaEvents::PRE_DELETE,
            new MediaEvent($entity)
        );

        parent::delete($entity);

        $this->getEventDispatcher()->dispatch(
            MediaEvents::POST_DELETE,
            new MediaEvent($entity)
        );
    }

    /**
     * Add storage mapper
     *
     * @param StorageMapperInterface $storageMapper
     */
    public function addStorageMapper(StorageMapperInterface $storageMapper)
    {
        $this->storageMappers[] = $storageMapper;
    }

    /**
     * Get storage provider
     *
     * @param string providerServiceName
     * @return Gaufrette\Filesystem The storage provider.
     * @throw UndefinedStorageMapperException
     */
    public function getStorageProvider($providerServiceName)
    {
        foreach($this->storageMappers as $storageMapper) {
            if($providerServiceName == $storageMapper->getStorageProviderServiceName()) {
                return $storageMapper->getStorageProvider();
            }
        }

        throw new UndefinedStorageMapperException($providerServiceName);
    }

    /**
     * Guess a storage mapper based on the given mediaRaw.
     *
     * @param string $mediaPath
     * @return StorageMapperInterface
     * @throw NoMatchedStorageProviderException
     */
    protected function guessStorageMapper($mediaPath)
    {
        foreach ($this->storageMappers as $storageMapper) {
            if ($storageMapper->checkRules($mediaPath)) {
                return $storageMapper;
            }
        }

        throw new NoMatchedStorageMapperException();
    }

    /**
     * Add metadata extractor
     *
     * @param MetadataExtractorInterface $metadataExtractor
     */
    public function addMetadataExtractor(MetadataExtractorInterface $metadataExtractor)
    {
        $this->metadataExtractors[] = $metadataExtractor;
    }

    /**
     * Guess a metadata extractor based on the given mime type
     *
     * @param string $mimeType
     * @return MetadataExtractorInterface
     */
    protected function guessMetadataExtractor($mimeType)
    {
        foreach ($this->metadataExtractors as $metadataExtractor) {
            if ($metadataExtractor->checkMimeType($mimeType)) {
                return $metadataExtractor;
            }
        }
    }

    /**
     * Add media transformer
     *
     * @param MediaTransformerInterface $mediaTransformer
     */
    public function addMediaTransformer(MediaTransformerInterface $mediaTransformer)
    {
        $this->mediaTransformers[] = $mediaTransformer;
    }

    /**
     * Guess a transformer on the given format
     *
     * @param string $format
     * @return MediaTransformerInterface
     */
    protected function guessMediaTransformer($format)
    {
        foreach ($this->mediaTransformers as $mediaTransformer) {
            if ($mediaTransformer->checkFormat($format)) {
                return $mediaTransformer;
            }
        }

        throw new NoMatchedTransformerException($format);
    }

    /**
     * Retrieve mediaRaw
     *
     * @param string $reference
     * @return array The media
     */
    public function retrieveMedia($reference)
    {
        $media = $this->findOneBy(array('reference' => $reference));

        if (!$media) {
            throw new MediaNotFoundException($reference);
        }

        return $media;
    }

    /**
     * Generate a unique rereference for a mediaRaw
     *
     * @param string $source
     * @param UploadedFile $mediaRaw
     *
     * @return string
     */
    public function generateMediaReference($source, UploadedFile $mediaRaw)
    {
        $now = new \DateTime();

        return sprintf('%s-%s-%s-%d',
            sprintf("%u", crc32($source)),
            $now->format('U'),
            md5(sprintf("%s%s%s",
              $mediaRaw->getClientMimeType(),
              $mediaRaw->getClientOriginalName(),
              $mediaRaw->getClientSize()
            )),
            rand(0, 9999)
        );
    }

    /**
     * Add Media
     *
     * @param UploadedFile $mediaRaw
     * @param string $source
     * @param string $name
     * @param string $description
     * @return Media
     */
    public function addMedia(UploadedFile $mediaRaw, $source = null, $name = null, $description = null)
    {
        $reference = $this->generateMediaReference($source, $mediaRaw);

        $media = $this->findOneBy(array('reference' => $reference));

        if ($media) {
            throw new MediaAlreadyExistException();
        }

        // Keep media information before handle the file
        $mimeType = $mediaRaw->getMimeType();
        $extension = $mediaRaw->guessExtension();
        $name = is_null($name) ? $mediaRaw->getClientOriginalName() : $name;
        $description = is_null($description) ? $mediaRaw->getClientOriginalName() : $description;

        // Store the media at the default path
        $mediaRaw->move($this->getDefaultStorePath(), $reference);
        $defaultMediaPath = sprintf('%s/%s', $this->getDefaultStorePath(), $reference);

        // Guess a storage provider and use it to store the media
        $storageMapper = $this->guessStorageMapper($defaultMediaPath);
        $storageMapper->getStorageProvider()->write(
            $reference,
            file_get_contents($defaultMediaPath)
        );
        $providerServiceName = $storageMapper->getStorageProviderServiceName();

        // Keep media informations in database
        $media = new Media();

        $media->setSource($source);
        $media->setReference($reference);
        $media->setExtension($extension);
        $media->setProviderServiceName($providerServiceName);
        $media->setName($name);
        $media->setDescription($description);
        $media->setSize(filesize($defaultMediaPath));
        $media->setMimeType($mimeType);

        $media->setMetadata($this
            ->guessMetadataExtractor($mimeType)
            ->extract($defaultMediaPath)
        );

        $this->add($media);

        // Remove the media if a provider was well guess and used, and the media entity stored.
        unlink($defaultMediaPath);

        return $media;
    }

    /**
     * Delete mediaRaw
     *
     * @param string $reference
     */
    public function deleteMedia($reference)
    {
        $media = $this->retrieveMedia($reference);
        $storageProvider = $this->getStorageProvider($media->getProviderServiceName());
        $this->delete($media);
    }

    /**
     * transform a given Media to a ResponseMedia based on given parameters
     *
     * @param Media $media
     * @param array $options
     * @return ResponseMedia
     */
    public function transform(Media $media, $options)
    {
        $mediaTransformer = $this->guessMediaTransformer($options['format']);

        return $mediaTransformer->transform(
            $this->getStorageProvider($media->getProviderServiceName()),
            $media,
            $options
        );
    }

    /**
     * Get media public uri
     *
     * @param Media $media
     *
     * @return string
     */
    public function getMediaPublicUri(Media $media)
    {
        return sprintf('%s/media/%s',
            $this->getApiPublicEndpoint(),
            $media->getReference()
        );
    }

}
