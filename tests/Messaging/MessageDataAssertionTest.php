<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 8/25/15 - 3:50 PM
 */
namespace ProophTest\Common\Messaging;

use Prooph\Common\Messaging\MessageDataAssertion;
use Prooph\Common\Messaging\NoOpMessageConverter;
use ProophTest\Common\Mock\DoSomething;
use Rhumsaa\Uuid\Uuid;

/**
 * Class MessageDataAssertionTest
 *
 * @package ProophTest\Common\Messaging
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageDataAssertionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_asserts_message_data_returned_by_the_no_op_message_converter()
    {
        $testAssertions = new DoSomething(['test' => 'assertions']);

        $messageConverter = new NoOpMessageConverter();

        MessageDataAssertion::assert($messageConverter->convertToArray($testAssertions));

        //No exception thrown means test green
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider
     * @dataProvider provideMessageDataWithMissingKey
     */
    public function it_throws_exception_if_message_data_is_invalid($messageData, $errorMessage)
    {
        $this->setExpectedException('\InvalidArgumentException', $errorMessage);
        MessageDataAssertion::assert($messageData);
    }

    public function provideMessageDataWithMissingKey()
    {
        $uuid = Uuid::uuid4()->toString();
        $payload = ['foo' => ['bar' => ['baz' => 100]]];
        $metadata = ['key' => 'value', 'string' => 'scalar'];
        $createdAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        return [
            [
                'message-data',
                'MessageData must be an array'
            ],
            [
                //#1 uuid is missing
                ['message_name' => 'test-message', 'version' => 1, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key uuid'
            ],
            [
                //#2 message_name missing
                ['uuid' => $uuid, 'version' => 1, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key message_name'
            ],
            [
                //#3 version missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key version'
            ],
            [
                //#4 payload missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 1, 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key payload'
            ],
            [
                //#5 metadata missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 1, 'payload' => $payload, 'created_at' => $createdAt],
                'MessageData must contain a key metadata'
            ],
            [
                //#6 created at missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 1, 'payload' => $payload, 'metadata' => $metadata],
                'MessageData must contain a key created_at'
            ],
            [
                //#7 invalid uuid string
                ['uuid' => 'invalid', 'message_name' => 'test-message', 'version' => 1, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'uuid must be a valid UUID string'
            ],
            [
                //#8 message name to short
                ['uuid' => $uuid, 'message_name' => 'te', 'version' => 1, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'message_name must be string with at least 3 chars length'
            ],
            [
                //#9 version must be an unsigned integer
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => -1, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'version must be an unsigned integer'
            ],
            [
                //#10 payload must be an array
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 0, 'payload' => 'string', 'metadata' => $metadata, 'created_at' => $createdAt],
                'payload must be an array'
            ],
            [
                //#11 payload must not contain objects
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 0, 'payload' => ['sub' => ['key' => new \stdClass()]], 'metadata' => $metadata, 'created_at' => $createdAt],
                'payload must only contain arrays and scalar values'
            ],
            [
                //#12 metadata must be an array
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 0, 'payload' => $payload, 'metadata' => 'string', 'created_at' => $createdAt],
                'metadata must be an array'
            ],
            [
                //#13 metadata must not contain non scalar values
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 0, 'payload' => $payload, 'metadata' => ['sub_array' => []], 'created_at' => $createdAt],
                'A metadata value must have a scalar type. Got array for sub_array'
            ],
            [
                //#14 created_at must be of type \DateTimeInterface
                ['uuid' => $uuid, 'message_name' => 'test-message', 'version' => 0, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => '2015-08-25 16:30:10'],
                'created_at must be of type DateTimeInterface. Got string'
            ],
        ];
    }
}
