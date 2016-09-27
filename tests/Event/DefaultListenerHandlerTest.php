<?php
/**
 * This file is part of the prooph/common.
 *  (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 *  (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare (strict_types=1);

namespace ProophTest\Common\Event;

use PHPUnit_Framework_TestCase as TestCase;
use Prooph\Common\Event\DefaultListenerHandler;

/**
 * Class DefaultListenerHandlerTest
 * @package ProophTest\Common\Event
 */
class DefaultListenerHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_exception_when_invalid_listener_given() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Given parameter listener should be callable or an instance of ActionEventListener');

        new DefaultListenerHandler('invalid');
    }
}
