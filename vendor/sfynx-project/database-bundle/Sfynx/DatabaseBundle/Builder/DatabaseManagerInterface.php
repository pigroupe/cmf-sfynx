<?php
/**
 * This file is part of the <Database> project.
 *
 * @subpackage   Database
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\DatabaseBundle\Builder;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * DatabaseManagerInterface interface.
 *
 * @subpackage   Database
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface DatabaseManagerInterface
{
     public function run(OutputInterface $output, Array $options = null);
}