<?php

namespace Tms\Bundle\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;


class CleanUpOrphanMediaFilesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tms-media:cleanup:orphan-files')
            ->setDescription('Clean orphans files without associated media')
            ->addArgument('folderPath', InputArgument::REQUIRED, 'The folder\'s path')
            ->addOption('force','f', InputOption::VALUE_NONE, 'if present the files will be removed from filesystem')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command.

<info>php app/console %command.name% /path/to/my/mediaFiles/ -f </info>

If you have some doubt about media integrity, you could check it by this way.

<info>php app/console %command.name% /path/to/my/mediaFiles/ </info>

Alternatively, you can clean the orphan media files:

<info>php app/console %command.name% /path/to/my/mediaFiles/ --force</info>

EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {       
        $timeStart = microtime(true);

        $folderPath = $input->getArgument('folderPath');
        $output->writeln(sprintf('<info>Start Media Cleaner on %s</info>', $folderPath));
        $finder = new Finder();
        $finder->files()->in($folderPath);
        $count = 0;
        $rcount = 0;
        foreach ($finder as $file) {
            $media = $this->getContainer()->get('tms_media.manager.media')->findOneBy(array(
                'reference' => $file
            ));

            if (null === $media) {
                $output->writeln(sprintf('<info>File %s has no media associated</info>', $file));
                if ($input->getOption('force')) {

                    if(@unlink($file)){
                        $output->writeln(sprintf('<info>Remove %s</info>', $file));
                        $rcount++;
                    } else {
                        $output->writeln(sprintf('<error>Can\'t remove %s</error>', $file));
                    }

                }
            }
            $count++;
        }

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;
        $output->writeln(sprintf('<comment>Ending Media Cleaner [%d sec] %d files processed, %d files removed, %d files untouched</comment>',            
            $time,
            $count,
            $rcount,
            ($count-$rcount)
        ));
    }
}
