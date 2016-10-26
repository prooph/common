<?php
/**
 * This file is part of the prooph/common.
 * (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Messaging;

use Assert\Assertion;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

/**
 * Class DomainMessage
 *
 * Base class for commands, domain events and queries. All are messages but differ in their intention.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
abstract class DomainMessage implements Message
{
    /**
     * @var string
     */
    protected $messageName;

    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @var DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * Return message payload as array
     *
     * The payload should only contain scalar types and sub arrays.
     * The payload is normally passed to json_encode to persist the message or
     * push it into a message queue.
     */
    abstract public function payload(): array;

    abstract protected function setPayload(array $payload): void;

    public static function fromArray(array $messageData): DomainMessage
    {
        MessageDataAssertion::assert($messageData);

        $messageRef = new \ReflectionClass(get_called_class());

        /** @var $message DomainMessage */
        $message = $messageRef->newInstanceWithoutConstructor();

        $message->uuid = Uuid::fromString($messageData['uuid']);
        $message->messageName = $messageData['message_name'];
        $message->metadata = $messageData['metadata'];
        $message->createdAt = $messageData['created_at'];
        $message->setPayload($messageData['payload']);

        return $message;
    }

    protected function init(): void
    {
        if ($this->uuid === null) {
            $this->uuid = Uuid::uuid4();
        }

        if ($this->messageName === null) {
            $this->messageName = get_called_class();
        }

        if ($this->createdAt === null) {
            $time = (string) microtime(true);
            if (false === strpos($time, '.')) {
                $time .= '.0000';
            }
            $this->createdAt = \DateTimeImmutable::createFromFormat('U.u', $time);
        }
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function toArray(): array
    {
        return [
            'message_name' => $this->messageName,
            'uuid' => $this->uuid->toString(),
            'payload' => $this->payload(),
            'metadata' => $this->metadata,
            'created_at' => $this->createdAt(),
        ];
    }

    public function messageName(): string
    {
        return $this->messageName;
    }

    public function withMetadata(array $metadata): Message
    {
        $messageData = $this->toArray();

        $messageData['metadata'] = $metadata;

        return static::fromArray($messageData);
    }

    /**
     * Returns new instance of message with $key => $value added to metadata
     *
     * Given value must have a scalar type.
     */
    public function withAddedMetadata(string $key, $value): Message
    {
        Assertion::notEmpty($key, 'Invalid key');

        $messageData = $this->toArray();

        $messageData['metadata'][$key] = $value;

        return static::fromArray($messageData);
    }
}
