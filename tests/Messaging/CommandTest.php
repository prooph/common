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
use Prooph\Common\Messaging\RemoteMessage;
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

        $this->command = Command::fromArray([
            'name' => 'TestCommand',
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

        $commandCopy = Command::fromArray($commandData);

        $this->assertEquals($commandData, $commandCopy->toArray());
    }

    /**
     * @test
     */
    function it_can_be_converted_to_remote_message_and_back()
    {
        $remoteMessage = $this->command->toRemoteMessage();

        $this->assertInstanceOf(RemoteMessage::class, $remoteMessage);

        $commandCopy = Command::fromRemoteMessage($remoteMessage);

        $this->assertEquals($this->command->toArray(), $commandCopy->toArray());
    }
} 