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

use PHPUnit\Framework\TestCase;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\Common\Messaging\DomainMessage;
use ProophTest\Common\Mock\SomethingWasDone;
use Ramsey\Uuid\Uuid;

class DomainEventTest extends TestCase
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
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $this->domainEvent = SomethingWasDone::fromArray([
            'message_name' => 'TestDomainEvent',
            'uuid' => $this->uuid->toString(),
            'created_at' => $this->createdAt,
            'payload' => ['event' => 'payload'],
            'metadata' => ['event' => 'metadata'],
        ]);
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $this->assertEquals('TestDomainEvent', $this->domainEvent->messageName());
    }

    /**
     * @test
     */
    public function it_has_a_uuid(): void
    {
        $this->assertTrue($this->uuid->equals($this->domainEvent->uuid()));
    }

    /**
     * @test
     */
    public function it_has_created_at_information(): void
    {
        $this->assertEquals($this->createdAt->format(\DateTime::ISO8601), $this->domainEvent->createdAt()->format(\DateTime::ISO8601));
    }

    /**
     * @test
     */
    public function it_has_payload(): void
    {
        $this->assertEquals(['event' => 'payload'], $this->domainEvent->payload());
    }

    /**
     * @test
     */
    public function it_has_metadata(): void
    {
        $this->assertEquals(['event' => 'metadata'], $this->domainEvent->metadata());
    }

    /**
     * @test
     */
    public function it_can_be_converted_to_array_and_back(): void
    {
        $commandData = $this->domainEvent->toArray();

        $commandCopy = SomethingWasDone::fromArray($commandData);

        $this->assertEquals($commandData, $commandCopy->toArray());
    }

    /**
     * @test
     */
    public function it_is_of_type_event(): void
    {
        $this->assertEquals(DomainMessage::TYPE_EVENT, $this->domainEvent->messageType());
    }
}
