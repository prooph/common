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
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\DomainMessage;
use ProophTest\Common\Mock\DoSomething;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CommandTest extends TestCase
{
    /**
     * @var Command
     */
    private $command;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var UuidInterface
     */
    private $uuid;

    protected function setUp()
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $this->command = DoSomething::fromArray([
            'message_name' => 'TestCommand',
            'uuid' => $this->uuid->toString(),
            'created_at' => $this->createdAt,
            'payload' => ['command' => 'payload'],
            'metadata' => ['command' => 'metadata'],
        ]);
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $this->assertEquals('TestCommand', $this->command->messageName());
    }

    /**
     * @test
     */
    public function it_has_a_uuid(): void
    {
        $this->assertTrue($this->uuid->equals($this->command->uuid()));
    }

    /**
     * @test
     */
    public function it_has_created_at_information(): void
    {
        $this->assertEquals($this->createdAt->format(\DateTime::ISO8601), $this->command->createdAt()->format(\DateTime::ISO8601));
    }

    /**
     * @test
     */
    public function it_has_payload(): void
    {
        $this->assertEquals(['command' => 'payload'], $this->command->payload());
    }

    /**
     * @test
     */
    public function it_has_metadata(): void
    {
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
    }

    /**
     * @test
     */
    public function it_can_be_converted_to_array_and_back(): void
    {
        $commandData = $this->command->toArray();

        $commandCopy = DoSomething::fromArray($commandData);

        $this->assertEquals($commandData, $commandCopy->toArray());
    }

    /**
     * @test
     */
    public function it_returns_new_instance_with_replaced_metadata(): void
    {
        $newCommand = $this->command->withMetadata(['other' => 'metadata']);

        $this->assertNotSame($this->command, $newCommand);
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
        $this->assertEquals(['other' => 'metadata'], $newCommand->metadata());
    }

    /**
     * @test
     */
    public function it_returns_new_instance_with_added_metadata(): void
    {
        $newCommand = $this->command->withAddedMetadata('other', 'metadata');

        $this->assertNotSame($this->command, $newCommand);
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
        $this->assertEquals(['command' => 'metadata', 'other' => 'metadata'], $newCommand->metadata());
    }

    /**
     * @test
     */
    public function it_is_initialized_with_defaults(): void
    {
        $command = new DoSomething(['command' => 'payload']);

        $this->assertEquals(DoSomething::class, $command->messageName());
        $this->assertInstanceOf(UuidInterface::class, $command->uuid());
        $this->assertEquals((new \DateTimeImmutable())->format('Y-m-d'), $command->createdAt()->format('Y-m-d'));
        $this->assertEquals(['command' => 'payload'], $command->payload());
        $this->assertEquals([], $command->metadata());
    }

    /**
     * @test
     */
    public function it_is_of_type_command(): void
    {
        $this->assertEquals(DomainMessage::TYPE_COMMAND, $this->command->messageType());
    }
}
