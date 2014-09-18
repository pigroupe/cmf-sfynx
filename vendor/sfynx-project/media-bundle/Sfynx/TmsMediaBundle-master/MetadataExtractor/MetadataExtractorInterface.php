<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\MetadataExtractor;

interface MetadataExtractorInterface
{
    /**
     * Check the mimeType.
     *
     * @param string $file
     * @return boolean
     */
    public function checkMimeType($mimeType);

    /**
     * Extract media metadata
     *
     * @param string $mediaPath
     * @return array
     */
    public function extract($mediaPath);
}
