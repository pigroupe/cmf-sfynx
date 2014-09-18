<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\Media\Transformer;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tms\Bundle\MediaBundle\Entity\Media;
use Tms\Bundle\MediaBundle\Media\ResponseMedia;
use Tms\Bundle\MediaBundle\Media\ImageMedia;
use Tms\Bundle\MediaBundle\Exception\ImagickException;
use Gaufrette\Filesystem;

class ImageMediaTransformer extends AbstractMediaTransformer
{
    private $cacheDirectory;
    private static $fileinfo;

    /**
     * @param ImageMedia $imageMedia
     * @param string $cacheDirectory
     */
    public function __construct($cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
        self::$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAvailableFormats()
    {
        return array('jpg', 'jpeg', 'png', 'gif');
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setOptional(array(
            'resize',
            'scale',
            'grayscale',
            'rotate',
            'width',
            'height',
            'maxwidth',
            'maxheight',
            'minwidth',
            'minheight',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function process(Filesystem $storageProvider, Media $media, array $options = array())
    {
        $originalContent = $storageProvider->read($media->getReference());

        if ($this->getFormat($options) === $media->getExtension() && count($options) === 1) {

            return $this->createResponseMedia(
                $originalContent,
                $media->getMimeType(),
                $media->getSize(),
                $media->getCreatedAt()
            );
        }

        $cachedImageSourcePath = $this->getCachedImageSourcePath($media->getReference(), $options);
        if ($this->hasCachedImage($cachedImageSourcePath)) {
            return $this->getCachedImage($cachedImageSourcePath);
        }

        $imageMedia = $this->createImageMedia($originalContent, $media);
        $reflectionClass = new \ReflectionClass($imageMedia);
        foreach ($options as $function => $argument) {
            if (!$reflectionClass->hasMethod($function)) {
                continue;
            }
            $reflectionMethod = $reflectionClass->getMethod($function);
            $parameters = $reflectionMethod->getParameters();
            if (!count($parameters)) {
                $imageMedia->$function();
            } elseif (count($parameters) === 1) {
                $imageMedia->$function($argument);
            } else {
                $arguments = array();
                foreach ($parameters as $parameter) {
                    $arguments[$parameter->getName()] = isset($options[$parameter->getName()]) ?
                        $options[$parameter->getName()] :
                        null
                    ;
                }
                call_user_func_array(array($imageMedia, $function), $arguments);
            }
        }

        $imageMedia
            ->quality(95)
            ->save($cachedImageSourcePath)
        ;

        return $this->createResponseMedia(
            file_get_contents($cachedImageSourcePath),
            finfo_file(self::$fileinfo, $cachedImageSourcePath),
            filesize($cachedImageSourcePath),
            new \DateTime('now')
        );
    }

    /**
     * Get the cached image source path based on the media and the requested options
     *
     * @param string $reference
     * @return string
     */
    private function getCachedImageSourcePath($reference, $options)
    {
        $imageCacheName = sprintf('%s_%s.%s',
            $reference,
            sprintf("%u", crc32(serialize($options))),
            $this->getFormat($options)
        );
        $imageCachePath = sprintf('%s/%s', $this->cacheDirectory, $imageCacheName);

        return $imageCachePath;
    }

    /**
     * Has cached image
     *
     * @param string $sourcePath
     * @return boolean
     */
    private function hasCachedImage($sourcePath)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        return true;
    }

    /**
     * Get the cached image if exist
     *
     * @param string $sourcePath
     * @return ResponseMedia | null
     */
    private function getCachedImage($sourcePath)
    {
        if (!file_exists($sourcePath)) {
            return null;
        }

        $date = new \DateTime();
        $date->setTimestamp(filemtime($sourcePath));
        return $this->createResponseMedia(
            file_get_contents($sourcePath),
            finfo_file(self::$fileinfo, $sourcePath),
            filesize($sourcePath),
            $date
        );
    }

    /**
     * Create image media
     *
     * @param File $originalContent
     * @param Media $media
     * @return ImageMedia
     */
    private function createImageMedia($originalContent, $media)
    {
        $sourcePath = sprintf('%s/%s.%s.tmp',
            $this->cacheDirectory,
            $media->getReference(),
            $media->getExtension()
        );
        file_put_contents($sourcePath, $originalContent);

        return new ImageMedia($media, $sourcePath);
    }

    /**
     * Create a response media
     *
     * @param string $content
     * @param string $mimeType
     * @param integer $size
     * @param \DateTime $date
     * @return ResponseMedia
     */
    protected function createResponseMedia($content, $mimeType, $size, $date)
    {
        $responseMedia = new ResponseMedia();
        $responseMedia
            ->setContent($content)
            ->setContentType($mimeType)
            ->setContentLength($size)
            ->setLastModifiedAt($date)
        ;

        return $responseMedia;
    }
}
