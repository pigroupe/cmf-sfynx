<?php
/**
 * This file is part of the <Auth> project.
 * 
 * @category   Auth
 * @package    Tests
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Tests\Handler;

use Sfynx\AuthBundle\Tests\WebTestCase;

/**
 * @category   Auth
 * @package    Tests
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ExceptionTest extends WebTestCase
{
    public function testNotFoundException()
    {
        /** @var $client Client */
        $client = static::createClient();
        $crawler = $client->request('GET', '/one-unknown-page/');
        $this->assertStatusCode(404, $client);
    }
}
