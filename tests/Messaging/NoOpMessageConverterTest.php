<?php
/**
 * This file is part of the prooph/common.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ProophTest\Common\Messaging;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prooph\Common\Messaging\DomainMessage;
use Prooph\Common\Messaging\Message;
use Prooph\Common\Messaging\NoOpMessageConverter;

class NoOpMessageConverterTest extends TestCase
{
    /**
     * @test
     */
    public function it_converts_to_array(): void
    {
        $messageMock = $this->getMockForAbstractClass(DomainMessage::class, [], '', true, true, true, ['toArray']);
        $messageMock->expects($this->once())->method('toArray');

        $converter = new NoOpMessageConverter();
        $converter->convertToArray($messageMock);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_message_is_not_a_domain_message(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('was expected to be instanceof of "Prooph\Common\Messaging\DomainMessage" but is not');

        $messageMock = $this->getMockForAbstractClass(Message::class);

        $converter = new NoOpMessageConverter();
        $converter->convertToArray($messageMock);
    }
}
