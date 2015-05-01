<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.03.14 - 18:33
 */

namespace ProophTest\Common\Messaging;

use Prooph\Common\Messaging\MessageHeader;
use Rhumsaa\Uuid\Uuid;

/**
 * Class MessageHeaderTest
 *
 * @package ProophTest\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
class MessageHeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_has_a_uuid()
    {
        $uuid = Uuid::uuid4();

        $header = new MessageHeader($uuid, new \DateTimeImmutable(), 1, MessageHeader::TYPE_COMMAND);

        $this->assertEquals($uuid->toString(), $header->uuid()->toString());
    }

    /**
     * @test
     */
    public function it_has_a_created_at_datetime_immutable()
    {
        $createdAt = new \DateTimeImmutable();

        $header = new MessageHeader(Uuid::uuid4(), $createdAt, 1, MessageHeader::TYPE_COMMAND);

        $this->assertEquals($createdAt->getTimestamp(), $header->createdAt()->getTimestamp());
    }

    /**
     * @test
     */
    public function it_has_a_version()
    {
        $header = new MessageHeader(Uuid::uuid4(), new \DateTimeImmutable(), 1, MessageHeader::TYPE_COMMAND);

        $this->assertEquals(1, $header->version());
    }

    /**
     * @test
     */
    public function it_has_a_type()
    {
        $header = new MessageHeader(Uuid::uuid4(), new \DateTimeImmutable(), 1, MessageHeader::TYPE_COMMAND);

        $this->assertEquals('command', $header->type());
    }

    /**
     * @test
     */
    function it_takes_metadata_as_an_optional_argument()
    {
        $header = new MessageHeader(Uuid::uuid4(), new \DateTimeImmutable(), 1, MessageHeader::TYPE_COMMAND, ['meta' => 'data']);

        $this->assertEquals(['meta' => 'data'], $header->metadata());
    }

    /**
     * @test
     */
    public function it_converts_itself_to_array_and_back()
    {
        $header = new MessageHeader(Uuid::uuid4(), new \DateTimeImmutable(), 1, MessageHeader::TYPE_COMMAND, ['meta' => 'data']);

        $headerArray = $header->toArray();

        $sameHeader = MessageHeader::fromArray($headerArray);

        $this->assertEquals($header->toArray(), $sameHeader->toArray());
    }
}
 