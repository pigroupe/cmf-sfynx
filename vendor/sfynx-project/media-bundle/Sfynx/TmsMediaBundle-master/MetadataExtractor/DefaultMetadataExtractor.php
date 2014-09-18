<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\MetadataExtractor;

class DefaultMetadataExtractor implements MetadataExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkMimeType($mimeType)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($mediaPath)
    {
        return array();
    }
}
