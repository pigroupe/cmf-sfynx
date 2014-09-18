<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaClientBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanOrphanMediaCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('tms:media:clean-orphan')
            ->setDescription('Clean orphan media')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command clean orphan media.
Here is an example of usage of this command <info>php app/console %command.name%</info>.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $medias = $em->getRepository('TmsMediaClientBundle:Media')->findAll();

        $providerHandler = $this->getContainer()->get('tms_media_client.storage_provider_handler');
        foreach ($medias as $media) {
            $storageProvider = $providerHandler->getStorageProvider($media->getProviderName());
            var_dump($storageProvider->getName(), $storageProvider->getMediaPublicUrl($media->getProviderReference()));
        }

        //die('TODO: Add soft delete on media when remove. Use this command to inform provider to delete the media, then do the job.');
    }
}
