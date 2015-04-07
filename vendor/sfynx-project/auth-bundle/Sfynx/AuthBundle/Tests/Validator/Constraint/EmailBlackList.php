<?php
/**
 * This file is part of the <Auth> project.
 * 
 * @category   Auth
 * @package    Tests
 * @subpackage Validator
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
namespace Sfynx\AuthBundle\Tests\Validator\Constraint;

use Sfynx\AuthBundle\Validator\Constraint\EmailBlackList;
use Sfynx\AuthBundle\Validator\Constraint\EmailBlackListValidator;
use Phake;

/**
 * @category   Auth
 * @package    Tests
 * @subpackage Validator
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class EmailBlackList extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidateData
     */
    public function testValidate($blackList, $email, $response)
    {
        $constraint = new EmailBlackList();

        $executionContext = Phake::mock('Symfony\Component\Validator\ExecutionContext');

        $validator = new EmailBlackListValidator();
        $validator->initialize($executionContext);
        $validator->setBlackList($blackList);
        $validator->validate($email, $constraint);

        Phake::verify($executionContext, Phake::times($response))->addViolation(Phake::anyParameters());
    }

    public function getValidateData()
    {
        return array(
            array(array('test.com'), "true@test.com", 1),
            array(array('test.com'), "test@false.com", 0),
        );
    }
}
