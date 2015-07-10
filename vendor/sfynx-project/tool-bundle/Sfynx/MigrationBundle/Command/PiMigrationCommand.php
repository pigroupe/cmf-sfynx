<?php
/**
 * This file is part of the <Migration> project.
 *
 * @category   Migration
 * @package    Command
 * @subpackage Migration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-2-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Command to execute migration since a specific version
 *
 * <code>
 * php app/console sfynx:migration --currentVersion 24
 * </code>
 *
 * @category   Migration
 * @package    Command
 * @subpackage Migration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-2-16
 */
class PiMigrationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sfynx:migration')
            ->setDescription('Migration Handler')
            ->addOption('currentVersion', null, InputOption::VALUE_REQUIRED, 'Force the version of migration')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Use another directory with all migration')
            ->addOption('test', null, InputOption::VALUE_NONE, 'For test');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $dialog \Symfony\Component\Console\Helper\DialogHelper */
        $dialog   = $this->getHelperSet()->get('dialog');

        // migration number
        $currentVersion = $input->getOption('currentVersion');
        $output->writeln('Current version : ' . $currentVersion);

        // folder
        $migrationFolder = $input->getOption('dir');
        if (is_null($migrationFolder)) {
            $migrationFolder  = $this->getContainer()->getParameter('sfynx.tool.migration.path_dir');
        }

        $finder = new Finder();
        $finder->files()->name('Migration_*.php')->in($migrationFolder)->sortByName();
        /** @var $file \Symfony\Component\Finder\SplFileInfo */
        foreach ($finder as $file) {
            $migrationName = $file->getBaseName('.php');
            $mivrationVersion = (int) str_replace('Migration_', '', $migrationName);

            if ($currentVersion < $mivrationVersion) {
            //if ($mivrationVersion == "24") {  // pour lancer la migration 24
                $output->writeln('Start ' . $migrationName);
                require_once($file->getRealpath());
                $var = new $migrationName($this->getContainer(), $output, $dialog);
                $output->writeln('End ' . $migrationName);
            }
        }
    }
}
