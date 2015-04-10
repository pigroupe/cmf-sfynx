<?php
/**
 * This file is part of the <Migration> project.
 *
 * @category   Migration
 * @package    Model
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI6GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MigrationBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperInterface;

/**
 * Abstract model of a migration file.
 *
 * @category   Migration
 * @package    Model
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI6GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
abstract class abstractMigration
{
    /**
     * @var ContainerInterface
     */     
    protected $container;  
    
    /** 
     * @var OutputInterface 
     */
    protected $output;

    /** 
     * @var DialogHelper
     */
    protected $dialog;

    /**
     * @var string $path_dir
     */
    protected $path_dir;
    
    public function __construct(ContainerInterface $container, OutputInterface $output, HelperInterface $dialog)
    {
        $this->container = $container;
        $this->path_dir  = $container->getParameter('sfynx.tool.migration.path_dir');
        $this->output    = $output;
        $this->dialog    = $dialog;

        if ($this->test()) {
            $this->PreUp();
            $this->Up();
            $this->PostUp();
        }
    }

    protected function test()
    {
        return true;
    }

    protected function PreUp()
    {
        // do something
    }

    abstract protected function Up();

    protected function PostUp()
    {
        // do something
    }

    protected function log($msg, $test = null)
    {
        if (is_null($test)) {
            $this->output->writeln("  $msg");
        } elseif ($test) {
            $this->output->writeln("  $msg <info>[OK]</info>");
        } else {
            $this->output->writeln("  $msg <error>[KO]</error>");
        }
    }
}
