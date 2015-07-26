<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/1/15 - 1:51 PM
 */
namespace ProophTest\Common\Messaging;

use Prooph\Common\Messaging\DomainEvent;
use Prooph\Common\Messaging\DomainMessage;
use Prooph\Common\Messaging\RemoteMessage;
use ProophTest\Common\Mock\SomethingWasDone;
use Rhumsaa\Uuid\Uuid;

final class DomainEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DomainEvent
     */
    private $domainEvent;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var Uuid
     */
    private $uuid;

    protected function setUp()
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new \DateTimeImmutable();

        $this->domainEvent = SomethingWasDone::fromArray([
            'message_name' => 'TestDomainEvent',
            'uuid' => $this->uuid->toString(),
            'version' => 1,
            'created_at' => $this->createdAt->format(\DateTime::ISO8601),
            'payload' => ['event' => 'payload'],
            'metadata' => ['event' => 'metadata']
        ]);
    }

    /**
     * @test
     */
    function it_has_a_name()
    {
        $this->assertEquals('TestDomainEvent', $this->domainEvent->messageName());
    }

    /**
     * @test
     */
    function it_has_a_uuid()
    {
        $this->assertTrue($this->uuid->equals($this->domainEvent->uuid()));
    }

    /**
     * @test
     */
    function it_has_a_version()
    {
        $this->assertEquals(1, $this->domainEvent->version());
    }

    /**
     * @test
     */
    function it_has_created_at_information()
    {
        $this->assertEquals($this->createdAt->format(\DateTime::ISO8601), $this->domainEvent->createdAt()->format(\DateTime::ISO8601));
    }

    /**
     * @test
     */
    function it_has_payload()
    {
        $this->assertEquals(['event' => 'payload'], $this->domainEvent->payload());
    }

    /**
     * @test
     */
    function it_has_metadata()
    {
        $this->assertEquals(['event' => 'metadata'], $this->domainEvent->metadata());
    }

    /**
     * @test
     */
    function it_can_be_converted_to_array_and_back()
    {
        $commandData = $this->domainEvent->toArray();

        $commandCopy = SomethingWasDone::fromArray($commandData);

        $this->assertEquals($commandData, $commandCopy->toArray());
    }

    /**
     * @test
     */
    function it_is_of_type_event()
    {
        $this->assertEquals(DomainMessage::TYPE_EVENT, $this->domainEvent->messageType());
    }
} 