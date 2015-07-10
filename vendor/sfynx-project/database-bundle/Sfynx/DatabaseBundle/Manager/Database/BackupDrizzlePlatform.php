<?php
/**
 * This file is part of the <Database> project.
 *
 * @uses AbstractManager
 * @subpackage   DB
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-06-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\DatabaseBundle\Manager\Database;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Output\OutputInterface;
use Sfynx\DatabaseBundle\Manager\Database\AbstractManager;

/**
 * Database factory for backup database with the Drizzle platform.
 *
 * @uses AbstractManager
 * @subpackage   DB
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class BackupDrizzlePlatform extends AbstractManager
{
    /**
     * Constructor.
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @param \Symfony\Component\DependencyInjection\ContainerInterface;
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(Connection $connection, ContainerInterface $container)
    {
        parent::__construct($connection, $container);
    }
    
    /**
     * Print in the content file the query for disable all table constraints in Oracle
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-28
     */
    protected function disableForeignKeys(){
    }
    
    /**
     * Print in the content file the query for enabled all table constraints in Oracle
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-28
     */
    protected function EnabledForeignKeys(){
    }

}