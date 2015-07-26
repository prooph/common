<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/1/15 - 12:53 PM
 */
namespace ProophTest\Common\Messaging;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\DomainMessage;
use ProophTest\Common\Mock\DoSomething;
use Rhumsaa\Uuid\Uuid;

final class CommandTest extends \PHPUnit_Framework_TestCase
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
     * @var Uuid
     */
    private $uuid;

    protected function setUp()
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new \DateTimeImmutable();

        $this->command = DoSomething::fromArray([
            'message_name' => 'TestCommand',
            'uuid' => $this->uuid->toString(),
            'version' => 1,
            'created_at' => $this->createdAt->format(\DateTime::ISO8601),
            'payload' => ['command' => 'payload'],
            'metadata' => ['command' => 'metadata']
        ]);
    }

    /**
     * @test
     */
    function it_has_a_name()
    {
        $this->assertEquals('TestCommand', $this->command->messageName());
    }

    /**
     * @test
     */
    function it_has_a_uuid()
    {
        $this->assertTrue($this->uuid->equals($this->command->uuid()));
    }

    /**
     * @test
     */
    function it_has_a_version()
    {
        $this->assertEquals(1, $this->command->version());
    }

    /**
     * @test
     */
    function it_has_created_at_information()
    {
        $this->assertEquals($this->createdAt->format(\DateTime::ISO8601), $this->command->createdAt()->format(\DateTime::ISO8601));
    }

    /**
     * @test
     */
    function it_has_payload()
    {
        $this->assertEquals(['command' => 'payload'], $this->command->payload());
    }

    /**
     * @test
     */
    function it_has_metadata()
    {
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
    }

    /**
     * @test
     */
    function it_can_be_converted_to_array_and_back()
    {
        $commandData = $this->command->toArray();

        $commandCopy = DoSomething::fromArray($commandData);

        $this->assertEquals($commandData, $commandCopy->toArray());
    }

    /**
     * @test
     */
    function it_returns_new_instance_with_updated_version()
    {
        $newCommand = $this->command->withVersion(2);

        $this->assertNotSame($this->command, $newCommand);
        $this->assertEquals(1, $this->command->version());
        $this->assertEquals(2, $newCommand->version());
    }

    /**
     * @test
     */
    function it_returns_new_instance_with_replaced_metadata()
    {
        $newCommand = $this->command->withMetadata(['other' => 'metadata']);

        $this->assertNotSame($this->command, $newCommand);
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
        $this->assertEquals(['other' => 'metadata'], $newCommand->metadata());
    }

    /**
     * @test
     */
    function it_returns_new_instance_with_added_metadata()
    {
        $newCommand = $this->command->withAddedMetadata('other', 'metadata');

        $this->assertNotSame($this->command, $newCommand);
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
        $this->assertEquals(['command' => 'metadata', 'other' => 'metadata'], $newCommand->metadata());
    }

    /**
     * @test
     */
    function it_is_initialized_with_defaults()
    {
        $command = new DoSomething(['command' => 'payload']);

        $this->assertEquals(DoSomething::class, $command->messageName());
        $this->assertInstanceOf(Uuid::class, $command->uuid());
        $this->assertEquals(0, $command->version());
        $this->assertEquals((new \DateTimeImmutable)->format('Y-m-d'), $command->createdAt()->format('Y-m-d'));
        $this->assertEquals(['command' => 'payload'], $command->payload());
        $this->assertEquals([], $command->metadata());
    }

    /**
     * @test
     */
    function it_is_of_type_command()
    {
        $this->assertEquals(DomainMessage::TYPE_COMMAND, $this->command->messageType());
    }
} 