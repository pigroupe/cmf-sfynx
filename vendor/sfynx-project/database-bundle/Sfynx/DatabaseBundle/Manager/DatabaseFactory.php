<?php
/**
 * This file is part of the <Database> project.
 * 
 * @uses DatabaseFactoryInterface
 * @subpackage   DB
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\DatabaseBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Platforms\DrizzlePlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Platforms\SQLServer2005Platform;
use Doctrine\DBAL\Platforms\SQLServer2008Platform;

use Sfynx\ToolBundle\Route\AbstractFactory;
use Sfynx\DatabaseBundle\Exception\DatabaseException;

use Sfynx\DatabaseBundle\Manager\Database\BackupDB2Platform;
use Sfynx\DatabaseBundle\Manager\Database\BackupDrizzlePlatform;
use Sfynx\DatabaseBundle\Manager\Database\BackupMySqlPlatform;
use Sfynx\DatabaseBundle\Manager\Database\BackupOraclePlatform;
use Sfynx\DatabaseBundle\Manager\Database\BackupPostgreSqlPlatform;
use Sfynx\DatabaseBundle\Manager\Database\BackupSqlitePlatform;
use Sfynx\DatabaseBundle\Manager\Database\BackupSQLServerPlatform;

use Sfynx\DatabaseBundle\Manager\Database\RestoreManager as Restore;
use Sfynx\DatabaseBundle\Builder\DatabaseFactoryInterface;

/**
 * Database factory for backup, restore, ... database.
 * 
 * @uses DatabaseFactoryInterface
 * @subpackage   DB
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DatabaseFactory extends AbstractFactory implements DatabaseFactoryInterface
{
    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
    
    /**
     * Return the backup factory.
     *
     * @return \Sfynx\DatabaseBundle\Manager\Database\AbstractManager
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-03
     */    
    public function getBackupFactory()
    {
        static $instance;
        if (!isset($instance))
        {
            // we get the DatabasePlatform for the connection.
            $platform    = $this->getDatabasePlatform();
            
            switch (true) {
                case ($platform instanceof MySqlPlatform) :
                    $instance =  new BackupMySqlPlatform($this->getConnection(), $this->getContainer());
                    break;
                case ($platform instanceof OraclePlatform) :
                    $instance =  new BackupOraclePlatform($this->getConnection(), $this->getContainer());
                    break;
                case ( ($platform instanceof SQLServerPlatform) || ($platform instanceof SQLServer2005Platform) || ($platform instanceof SQLServer2008Platform)):
                    $instance =  new BackupSQLServerPlatform($this->getConnection(), $this->getContainer());
                    break;
                   case ($platform instanceof PostgreSqlPlatform) :
                       $instance =  new BackupPostgreSqlPlatform($this->getConnection(), $this->getContainer());
                       break;
//                 case ($platform instanceof DB2Platform ):
//                     $instance =  new BackupDB2Platform($this->getConnection(), $this->getContainer());
//                     break;
//                 case ($platform instanceof DrizzlePlatform) :
//                     $instance =  new BackupDrizzlePlatform($this->getConnection(), $this->getContainer());
//                     break;
//                 case ($platform instanceof SqlitePlatform) :
//                     $instance =  new BackupSqlitePlatform($this->getConnection(), $this->getContainer());
//                     break;
                default :
                    throw DatabaseException::databasePlatformNotSupported();
                    break;
            }            
            
        }
        
        return $instance;        
    }
    
    /**
     * Return the Restore factory.
     *
     * @return \Sfynx\DatabaseBundle\Manager\Database\AbstractManager
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-03
     */    
    public function getRestoreFactory()
    {
        static $instance;
        if (!isset($instance))
        {
            // we get the DatabasePlatform for the connection.
            $platform    = $this->getDatabasePlatform();
            
            switch (true) {
                case ($platform instanceof DB2Platform ):
                    $instance =  new Restore($this->getConnection(), $this->getContainer());
                    break;
                case ($platform instanceof DrizzlePlatform) :
                    $instance =  new Restore($this->getConnection(), $this->getContainer());
                    break;
                case ($platform instanceof MySqlPlatform) :
                    $instance =  new Restore($this->getConnection(), $this->getContainer());
                    break;
                case ($platform instanceof OraclePlatform) :
                    $instance =  new Restore($this->getConnection(), $this->getContainer());
                    break;
                case ($platform instanceof PostgreSqlPlatform) :
                    $instance =  new Restore($this->getConnection(), $this->getContainer());
                    break;
                case ($platform instanceof SqlitePlatform) :
                    $instance =  new Restore($this->getConnection(), $this->getContainer());
                    break;
                case ( ($platform instanceof SQLServerPlatform) || ($platform instanceof SQLServer2005Platform) || ($platform instanceof SQLServer2008Platform)):
                    $instance =  new Restore($this->getConnection(), $this->getContainer());
                    break;
                default :
                    throw DatabaseException::databasePlatformNotSupported();
                    break;
            }
             
        }
        
        return $instance;        
     }
    
}