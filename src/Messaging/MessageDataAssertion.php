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

namespace Prooph\Common\Messaging;

use Assert\Assertion;
use DateTimeImmutable;

final class MessageDataAssertion
{
    /**
     * @param mixed $messageData
     *
     * @return void
     */
    public static function assert($messageData): void
    {
        Assertion::isArray($messageData, 'MessageData must be an array');
        Assertion::keyExists($messageData, 'message_name', 'MessageData must contain a key message_name');
        Assertion::keyExists($messageData, 'uuid', 'MessageData must contain a key uuid');
        Assertion::keyExists($messageData, 'payload', 'MessageData must contain a key payload');
        Assertion::keyExists($messageData, 'metadata', 'MessageData must contain a key metadata');
        Assertion::keyExists($messageData, 'created_at', 'MessageData must contain a key created_at');

        self::assertMessageName($messageData['message_name']);
        self::assertUuid($messageData['uuid']);
        self::assertPayload($messageData['payload']);
        self::assertMetadata($messageData['metadata']);
        self::assertCreatedAt($messageData['created_at']);
    }

    public static function assertUuid($uuid): void
    {
        Assertion::uuid($uuid, 'uuid must be a valid UUID string');
    }

    public static function assertMessageName($messageName): void
    {
        Assertion::minLength($messageName, 3, 'message_name must be string with at least 3 chars length');
    }

    public static function assertPayload($payload): void
    {
        Assertion::isArray($payload, 'payload must be an array');
        self::assertSubPayload($payload);
    }

    /**
     * @param mixed $payload
     */
    private static function assertSubPayload($payload): void
    {
        if (\is_array($payload)) {
            foreach ($payload as $subPayload) {
                self::assertSubPayload($subPayload);
            }

            return;
        }

        Assertion::nullOrscalar($payload, 'payload must only contain arrays and scalar values');
    }

    public static function assertMetadata($metadata): void
    {
        Assertion::isArray($metadata, 'metadata must be an array');

        foreach ($metadata as $key => $value) {
            Assertion::minLength($key, 1, 'A metadata key must be non empty string');
            Assertion::scalar($value, 'A metadata value must have a scalar type. Got ' . \gettype($value) . ' for ' . $key);
        }
    }

    public static function assertCreatedAt($createdAt): void
    {
        Assertion::isInstanceOf($createdAt, DateTimeImmutable::class, \sprintf(
            'created_at must be of type %s. Got %s',
            DateTimeImmutable::class,
            \is_object($createdAt) ? \get_class($createdAt) : \gettype($createdAt)
        ));
    }
}
