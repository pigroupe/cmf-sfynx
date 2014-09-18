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
use IDCI\Bundle\ExporterBundle\Service\Manager as Exporter;

class RestMediaTransformer extends AbstractMediaTransformer
{
    protected $exporter;

    /**
     * Constructor
     *
     * @param $Exporter;
     */
    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAvailableFormats()
    {
        return array('json', 'xml', 'csv');
    }

    /**
     * {@inheritdoc}
     */
    public function process(Filesystem $storageProvider, Media $media, array $options = array())
    {
        $responseMedia = new ResponseMedia();
        $export = $this->exporter->export(array($media), $options['format']);

        $responseMedia
            ->setContent($export->getContent())
            ->setContentType(sprintf(
                '%s; charset=UTF-8',
                $export->getContentType()
            ))
            ->setLastModifiedAt($media->getCreatedAt())
        ;

        return $responseMedia;
    }
}
