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

namespace ProophTest\Common\Messaging;

use Assert\InvalidArgumentException;
use PHPUnit_Framework_TestCase as TestCase;
use Prooph\Common\Messaging\DomainMessage;
use Prooph\Common\Messaging\Message;
use Prooph\Common\Messaging\NoOpMessageConverter;

/**
 * Class NoOpMessageConverterTest
 *
 * @package ProophTest\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class NoOpMessageConverterTest extends TestCase
{
    /**
     * @test
     */
    public function it_converts_to_array() : void
    {
        $messageMock = $this->prophesize(DomainMessage::class);
        //$messageMock->toArray()->willReturn([]);

        $converter = new NoOpMessageConverter();
        //$converter->convertToArray($messageMock->reveal());
    }

    /**
     * @test
     */
    public function it_throws_exception_when_message_is_not_a_domain_message() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('was expected to be instanceof of "Prooph\Common\Messaging\DomainMessage" but is not');

        $messageMock = $this->getMockForAbstractClass(Message::class);

        $converter = new NoOpMessageConverter();
        $converter->convertToArray($messageMock);
    }
}
