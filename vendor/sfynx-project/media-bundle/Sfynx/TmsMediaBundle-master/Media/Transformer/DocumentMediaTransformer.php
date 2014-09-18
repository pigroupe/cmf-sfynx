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
use Gaufrette\Filesystem;
use Tms\Bundle\MediaBundle\Media\ResponseMedia;
use Tms\Bundle\MediaBundle\Exception\UnavailabeTransformationException;

class DocumentMediaTransformer extends AbstractMediaTransformer
{
    /**
     * {@inheritdoc}
     */
    protected function getAvailableFormats()
    {
        return array('pdf', 'doc', 'docx', 'rtf', 'xls', 'xlsx', 'odt');
    }

    /**
     * {@inheritdoc}
     */
    public function process(Filesystem $storageProvider, Media $media, array $options = array())
    {
        $responseMedia = new ResponseMedia();

        if ($options['format'] !== $media->getExtension() || count($options) > 1) {
            throw new UnavailabeTransformationException($options);
        }

        $responseMedia
            ->setContent($storageProvider->read($media->getReference()))
            ->setContentType($media->getMimeType())
            ->setContentLength($media->getSize())
            ->setLastModifiedAt($media->getCreatedAt())
        ;

        return $responseMedia;
    }
}
