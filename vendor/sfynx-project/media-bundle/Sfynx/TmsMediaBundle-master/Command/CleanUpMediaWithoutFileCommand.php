<?php

namespace Tms\Bundle\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CleanUpMediaWithoutFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tms-media:cleanup:without-file-medias')
            ->setDescription('log media without associated files')
            // ->addArgument('folderPath', InputArgument::REQUIRED, 'The folder\'s path')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'if present the media record will be removed from entities')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command.

<info>php app/console %command.name% -f </info>

If you have some doubt about media integrity, you could check it by this way.

<info>php app/console %command.name% </info>

Alternatively, you can clean media entities and remove those have no file associated:

<info>php app/console %command.name% --force</info>

EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);
        $output->writeln(sprintf('<comment>Start Media Cleaner </comment>'));

        $mediaStorageManager = $this->getContainer()->get('tms_media.manager');
        $mediaEntityManager = $this->getContainer()->get('tms_media.manager.media');

        $medias = $mediaEntityManager->findAll();
        $count = $rcount = $pcount = 0;
        foreach ($medias as $media) {
            try {
                $providerServiceName = $media->getProviderServiceName();
                $reference = $media->getReference();
                $storageProvider = $mediaStorageManager->getStorageProvider($providerServiceName);
                $fileExists = $storageProvider->getAdapter()->exists($reference);

                if ($fileExists) {
                    $output->writeln(sprintf(
                        '<info>FOUND [%s] %s</info>',
                        $providerServiceName,
                        $reference
                    ));
                } else {
                    $output->writeln(sprintf(
                        '<question>NOT FOUND [%s] %s</question>',
                        $providerServiceName,
                        $reference
                    ));

                    if ($input->getOption('force')) {
                        $output->writeln(sprintf(
                            '<info;options=bold>REMOVE from entities media references :     %s </info;options=bold>',
                            $reference
                        ));

                        $mediaEntityManager->delete($media);
                        $rcount++;
                    }
                    $pcount++;
                }
            } catch (\Exception $e) {
                $output->writeln(sprintf(
                    '<error>FileSystem exception: %s</error>',
                    $e->getMessage()
                ));
            }
            $count++;
        }
        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;
        $output->writeln(sprintf(
            '<comment>Ending Media Cleaner [%d sec] %d problem encountered on a total of %d entities processed, %d entities removed, %d entities untouched</comment>',
            $time,
            $pcount,
            $count,
            $rcount,
            ($count-$rcount)
        ));
    }
}
