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
use DateTimeZone;
use http\Exception\InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Base class for commands, domain events and queries. All are messages but differ in their intention.
 */
abstract class DomainMessage implements Message
{
    private const DateTimeFormat = 'Y-m-d\TH:i:s.uP';

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

        $messageRef = new \ReflectionClass(\get_called_class());

        /** @var $message DomainMessage */
        $message = $messageRef->newInstanceWithoutConstructor();

        $message->uuid = Uuid::fromString($messageData['uuid']);
        $message->metadata = $messageData['metadata'];
        $message->createdAt = DateTimeImmutable::createFromFormat(
            self::DateTimeFormat,
            $messageData['created_at'],
            new DateTimeZone('UTC')
        );

        if (! $message->createdAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException(
                'Invalid string passed for created_at, expected format is: ' . self::DateTimeFormat
            );
        }

        $message->setPayload($messageData['payload']);

        return $message;
    }

    protected function init(): void
    {
        if ($this->uuid === null) {
            $this->uuid = Uuid::uuid4();
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
            'message_name' => $this->messageName(),
            'uuid' => $this->uuid->toString(),
            'payload' => $this->payload(),
            'metadata' => $this->metadata,
            'created_at' => $this->createdAt()->format('Y-m-d\TH:i:s.uP'),
        ];
    }

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
     */
    public function withAddedMetadata(string $key, $value): Message
    {
        Assertion::notEmpty($key, 'Invalid key');

        $message = clone $this;

        $message->metadata[$key] = $value;

        return $message;
    }
}
