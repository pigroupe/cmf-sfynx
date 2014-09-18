<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\Media\Transformer;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tms\Bundle\MediaBundle\Entity\Media;
use Tms\Bundle\MediaBundle\Media\ResponseMedia;
use Gaufrette\Filesystem;

abstract class AbstractMediaTransformer implements MediaTransformerInterface
{
    /**
     * Get available formats
     *
     * @return array
     */
    abstract protected function getAvailableFormats();

    /**
     * Process the transformation
     *
     * @param Filesystem $storageProvider
     * @param Media $media
     * @return ResponseMedia
     */
    abstract protected function process(Filesystem $storageProvider, Media $media, array $options = array());

    /**
     * {@inheritdoc}
     */
    public function checkFormat($format)
    {
        return in_array($format, $this->getAvailableFormats());
    }

    /**
     * Set default options
     *
     * @param OptionsResolverInterface
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'format'
        ));
        $resolver->setDefaults(array(
            'format' => $this->getAvailableFormats()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function transform(Filesystem $storageProvider, Media $media, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $options = $resolver->resolve($options);
        $responseMedia = $this
            ->process($storageProvider, $media, $options)
            ->setETag(sprintf('%s%s',
                $media->getReference(),
                null !== $this->getFormat($options) ? '.' . $this->getFormat($options) : ''
            ))
        ;

        return $responseMedia;
    }

    protected function getFormat(array $options)
    {
        return $options['format'];
    }
}
