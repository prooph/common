<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.03.14 - 18:43
 */

namespace ProophTest\Common\Messaging;

use Prooph\Common\Messaging\MessageHeader;
use Prooph\Common\Messaging\RemoteMessage;
use Rhumsaa\Uuid\Uuid;

/**
 * Class RemoteMessageTest
 *
 * @package Prooph\ServiceBusTest\Message
 * @author Alexander Miertsch <contact@prooph.de>
 */
class RemoteMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RemoteMessage
     */
    private $message;

    /**
     * @var MessageHeader
     */
    private $header;

    protected function setUp()
    {
        $this->header = new MessageHeader(Uuid::uuid4(), new \DateTimeImmutable(), 1, MessageHeader::TYPE_COMMAND);

        $this->message = new RemoteMessage('TestMessage', $this->header, array('data' => 'a test'));
    }

    /**
     * @test
     */
    public function it_has_a_name()
    {
        $this->assertEquals('TestMessage', $this->message->name());
    }

    /**
     * @test
     */
    public function it_has_a_header()
    {
        $this->assertInstanceOf(MessageHeader::class, $this->message->header());
    }

    /**
     * @test
     */
    public function it_has_a_payload()
    {
        $this->assertEquals(array('data' => 'a test'), $this->message->payload());
    }

    /**
     * @test
     */
    function it_can_be_converted_to_array_and_back()
    {
        $messageData = $this->message->toArray();

        $copiedMessage = RemoteMessage::fromArray($messageData);

        $this->assertEquals($this->message->toArray(), $copiedMessage->toArray());
    }
}
 