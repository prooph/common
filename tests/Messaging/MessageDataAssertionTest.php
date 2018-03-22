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
use Prooph\Common\Messaging\MessageDataAssertion;
use Prooph\Common\Messaging\NoOpMessageConverter;
use ProophTest\Common\Mock\DoSomething;
use Ramsey\Uuid\Uuid;

class MessageDataAssertionTest extends TestCase
{
    /**
     * @test
     */
    public function it_asserts_message_data_returned_by_the_no_op_message_converter(): void
    {
        $testAssertions = new DoSomething(['test' => 'assertions', ['null' => null]]);

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
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);

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
                'MessageData must be an array',
            ],
            [
                //#1 uuid is missing
                ['message_name' => 'test-message', 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key uuid',
            ],
            [
                //#2 message_name missing
                ['uuid' => $uuid, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key message_name',
            ],
            [
                //#3 payload missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key payload',
            ],
            [
                //#4 metadata missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'created_at' => $createdAt],
                'MessageData must contain a key metadata',
            ],
            [
                //#5 created at missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'metadata' => $metadata],
                'MessageData must contain a key created_at',
            ],
            [
                //#6 invalid uuid string
                ['uuid' => 'invalid', 'message_name' => 'test-message', 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'uuid must be a valid UUID string',
            ],
            [
                //#7 message name to short
                ['uuid' => $uuid, 'message_name' => 'te', 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'message_name must be string with at least 3 chars length',
            ],
            [
                //#8 payload must be an array
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => 'string', 'metadata' => $metadata, 'created_at' => $createdAt],
                'payload must be an array',
            ],
            [
                //#9 payload must not contain objects
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => ['sub' => ['key' => new \stdClass()]], 'metadata' => $metadata, 'created_at' => $createdAt],
                'payload must only contain arrays and scalar values',
            ],
            [
                //#10 metadata must be an array
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'metadata' => 'string', 'created_at' => $createdAt],
                'metadata must be an array',
            ],
            [
                //#11 metadata must not contain non scalar values
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'metadata' => ['sub_array' => []], 'created_at' => $createdAt],
                'A metadata value must have a scalar type. Got array for sub_array',
            ],
            [
                //#12 created_at must be of type \DateTimeImmutable
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'metadata' => $metadata, 'created_at' => '2015-08-25 16:30:10'],
                'created_at must be of type DateTimeImmutable. Got string',
            ],
        ];
    }
}
