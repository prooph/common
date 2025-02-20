<?php

/**
 * This file is part of prooph/common.
 * (c) 2014-2025 Alexander Miertsch <kontakt@codeliner.ws>
 * (c) 2015-2025 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Messaging;

use Assert\Assertion;
use DateTimeImmutable;
use DateTimeZone;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ReflectionClass;

/**
 * Base class for commands, domain events and queries. All are messages but differ in their intention.
 */
abstract class DomainMessage implements Message
{
    /**
     * @var string
     */
    protected $messageName;

    /**
     * @var UuidInterface
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

    abstract protected function setPayload(array $payload): void;

    public static function fromArray(array $messageData): DomainMessage
    {
        MessageDataAssertion::assert($messageData);

        $messageRef = new ReflectionClass(\get_called_class());

        /** @var $message DomainMessage */
        $message = $messageRef->newInstanceWithoutConstructor();

        $message->uuid = Uuid::fromString((string) $messageData['uuid']);
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
            $this->messageName = \get_class($this);
        }

        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        }
    }

    public function uuid(): UuidInterface
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

    /**
     * @return static
     */
    public function withMetadata(array $metadata): Message
    {
        $message = clone $this;

        $message->metadata = $metadata;

        return $message;
    }

    /**
     * Returns new instance of message with $key => $value added to metadata
     *
     * Given value must have a scalar type.
     *
     * @return static
     */
    public function withAddedMetadata(string $key, $value): Message
    {
        Assertion::notEmpty($key, 'Invalid key');

        $message = clone $this;

        $message->metadata[$key] = $value;

        return $message;
    }
}
