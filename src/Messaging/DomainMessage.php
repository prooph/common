<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/1/15 - 1:34 PM
 */
namespace Prooph\Common\Messaging;

use Assert\Assertion;
use Rhumsaa\Uuid\Uuid;

/**
 * Class DomainMessage
 *
 * Base class for commands and domain events. Both are messages but differ in their intention.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
abstract class DomainMessage implements HasMessageName
{
    const TYPE_COMMAND = 'command';
    const TYPE_EVENT   = 'event';
    const TYPE_QUERY   = 'query';

    /**
     * @var string
     */
    protected $messageName;

    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @var int
     */
    protected $version = 0;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * Override this in your message if you want to use another format
     *
     * @var string
     */
    protected $dateTimeFormat = \DateTime::ISO8601;

    /**
     * Should be either DomainMessage::TYPE_COMMAND or DomainMessage::TYPE_EVENT or DomainMessage::TYPE_QUERY
     *
     * @return string
     */
    abstract public function messageType();

    /**
     * Return message payload as array
     *
     * The payload should only contain scalar types and sub arrays.
     * The payload is normally passed to json_encode to persist the message or
     * push it into a message queue.
     *
     * @return array
     */
    abstract public function payload();

    /**
     * This method is called when message is instantiated named constructor fromArray
     *
     * @param array $payload
     * @return void
     */
    abstract protected function setPayload(array $payload);

    /**
     * Creates a new domain message from given array
     *
     * @param array $messageData
     * @return static
     */
    public static function fromArray(array $messageData)
    {
        Assertion::keyExists($messageData, 'uuid');
        Assertion::keyExists($messageData, 'message_name');
        Assertion::string($messageData['message_name'], 'message name needs to be string');
        Assertion::notEmpty($messageData['message_name'], 'message name must not be empty');
        Assertion::keyExists($messageData, 'version');
        Assertion::integer($messageData['version'], 'version needs to be an integer');
        Assertion::keyExists($messageData, 'payload');
        Assertion::isArray($messageData['payload'], 'payload needs to be an array');
        Assertion::keyExists($messageData, 'metadata');
        Assertion::keyExists($messageData, 'created_at');
        Assertion::isArray($messageData['metadata'], 'metadata needs to be an array');

        $messageRef = new \ReflectionClass(get_called_class());

        /** @var $message DomainMessage */
        $message = $messageRef->newInstanceWithoutConstructor();

        $message->uuid = Uuid::fromString($messageData['uuid']);
        $message->messageName = $messageData['message_name'];
        $message->version = $messageData['version'];
        $message->setPayload($messageData['payload']);
        $message->metadata = $messageData['metadata'];

        if (! $messageData['created_at'] instanceof \DateTimeInterface) {
            $messageData['created_at'] = \DateTimeImmutable::createFromFormat($message->dateTimeFormat, $messageData['created_at'], new \DateTimeZone('UTC'));
        }

        $message->createdAt = $messageData['created_at'];

        return $message;
    }

    /**
     * Call this method to initialize message with defaults
     */
    protected function init()
    {
        if ($this->uuid === null) {
            $this->uuid = Uuid::uuid4();
        }

        if ($this->messageName === null) {
            $this->messageName = get_called_class();
        }

        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        }
    }

    /**
     * @return Uuid
     */
    public function uuid()
    {
        return $this->uuid;
    }

    /**
     * @return int
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function metadata()
    {
        return $this->metadata;
    }

    /**
     * Returns an array copy of this command
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'message_name' => $this->messageName,
            'uuid' => $this->uuid->toString(),
            'version' => $this->version,
            'payload' => $this->payload(),
            'metadata' => $this->metadata,
            'created_at' => $this->createdAt()->format($this->dateTimeFormat)
        ];
    }

    /**
     * @return string Name of the message
     */
    public function messageName()
    {
        return $this->messageName;
    }

    /**
     * Returns a new instance of the message with given metadata
     *
     * @param array $metadata
     * @return DomainMessage
     */
    public function withMetadata(array $metadata)
    {
        $messageData = $this->toArray();

        $messageData['metadata'] = $metadata;

        return static::fromArray($messageData);
    }

    /**
     * Returns a new instance of the message with given version
     *
     * @param int $version
     * @return DomainMessage
     */
    public function withVersion($version)
    {
        Assertion::integer($version);

        $messageData = $this->toArray();

        $messageData['version'] = $version;

        return static::fromArray($messageData);
    }

    /**
     * Returns new instance of message with $key => $value added to metadata
     *
     * @param string $key
     * @param mixed $value
     * @return DomainMessage
     */
    public function withAddedMetadata($key, $value)
    {
        Assertion::string($key, 'Invalid key');
        Assertion::notEmpty($key, 'Invalid key');

        $messageData = $this->toArray();

        $messageData['metadata'][$key] = $value;

        return static::fromArray($messageData);
    }
}