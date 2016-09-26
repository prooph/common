<?php
/**
 * This file is part of the prooph/common.
 *  (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 *  (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare (strict_types=1);

namespace Prooph\Common\Messaging;

use Assert\Assertion;

/**
 * Class MessageDataAssertion
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class MessageDataAssertion
{
    /**
     * @param mixed $messageData
     */
    public static function assert($messageData)
    {
        Assertion::isArray($messageData, 'MessageData must be an array');
        Assertion::keyExists($messageData, 'message_name', 'MessageData must contain a key message_name');
        Assertion::keyExists($messageData, 'uuid', 'MessageData must contain a key uuid');
        Assertion::keyExists($messageData, 'version', 'MessageData must contain a key version');
        Assertion::keyExists($messageData, 'payload', 'MessageData must contain a key payload');
        Assertion::keyExists($messageData, 'metadata', 'MessageData must contain a key metadata');
        Assertion::keyExists($messageData, 'created_at', 'MessageData must contain a key created_at');

        self::assertMessageName($messageData['message_name']);
        self::assertUuid($messageData['uuid']);
        self::assertVersion($messageData['version']);
        self::assertPayload($messageData['payload']);
        self::assertMetadata($messageData['metadata']);
        self::assertCreatedAt($messageData['created_at']);
    }

    /**
     * @param string $uuid
     */
    public static function assertUuid($uuid)
    {
        Assertion::uuid($uuid, 'uuid must be a valid UUID string');
    }

    /**
     * @param string $messageName
     */
    public static function assertMessageName($messageName)
    {
        Assertion::minLength($messageName, 3, 'message_name must be string with at least 3 chars length');
    }

    /**
     * @param $version
     */
    public static function assertVersion($version)
    {
        Assertion::min($version, 0, 'version must be an unsigned integer');
    }

    /**
     * @param array $payload
     */
    public static function assertPayload($payload)
    {
        Assertion::isArray($payload, 'payload must be an array');
        self::assertSubPayload($payload);
    }

    /**
     * @param mixed $payload
     */
    private static function assertSubPayload($payload)
    {
        if (is_array($payload)) {
            foreach ($payload as $subPayload) {
                self::assertSubPayload($subPayload);
            }

            return;
        }

        Assertion::nullOrscalar($payload, 'payload must only contain arrays and scalar values');
    }

    /**
     * @param array $metadata
     */
    public static function assertMetadata($metadata)
    {
        Assertion::isArray($metadata, 'metadata must be an array');

        foreach ($metadata as $key => $value) {
            Assertion::minLength($key, 1, 'A metadata key must be non empty string');
            Assertion::scalar($value, 'A metadata value must have a scalar type. Got ' . gettype($value) . ' for ' . $key);
        }
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public static function assertCreatedAt($createdAt)
    {
        Assertion::isInstanceOf($createdAt, \DateTimeInterface::class, sprintf(
            'created_at must be of type %s. Got %s',
            \DateTimeInterface::class,
            is_object($createdAt)? get_class($createdAt) : gettype($createdAt)
        ));
    }
}
