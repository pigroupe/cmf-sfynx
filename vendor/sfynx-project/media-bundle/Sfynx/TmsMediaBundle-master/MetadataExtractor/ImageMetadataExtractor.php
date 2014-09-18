<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\MetadataExtractor;

class ImageMetadataExtractor implements MetadataExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkMimeType($mimeType)
    {
        return in_array($mimeType, array(
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/tiff',
            'image/vnd.microsoft.icon',
            'image/svg+xml',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function extract($mediaPath)
    {
        $data = getimagesize($mediaPath);

        $metadata = array_combine(
            array('width', 'height'),
            array($data[0], $data[1])
        );

        return $metadata;
    }
}
