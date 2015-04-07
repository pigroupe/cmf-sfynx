<?php
/**
 * This file is part of the <Core> project.
 *
 * @subpackage Core
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Tests\Event;

use Phake;

/**
 * @subpackage Core
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class SubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected function createEvent($subject)
    {
        $event = Phake::mock('Symfony\Component\EventDispatcher\GenericEvent');
        Phake::when($event)->getSubject()
            ->thenReturn($subject);

        return $event;
    }
}
