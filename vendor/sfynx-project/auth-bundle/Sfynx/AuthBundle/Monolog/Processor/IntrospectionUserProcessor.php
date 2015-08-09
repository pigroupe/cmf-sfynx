<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Monolog
 * @package    Processor
 * @subpackage User
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Monolog\Processor;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\AuthBundle\Entity\User;

/**
 * Custom login handler.
 * This allow you to execute code right after the user succefully logs in.
 * 
 * @category   Monolog
 * @package    Processor
 * @subpackage User
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class IntrospectionUserProcessor
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function processRecord(array $record)
    {
        if (isset($record['context']['user'])) {
            $user = $record['context']['user'];
            if ($user instanceof User ) {
                $record['extra']['user'] = array(
                    'username' => $user->getUsername(),
                    'email'    => $user->getEmail(),
                );
            }
        }

        return $record;
    }
}
