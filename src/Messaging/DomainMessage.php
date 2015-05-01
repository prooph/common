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
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @var int
     */
    protected $version;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @var array
     */
    protected $payload = array();

    /**
     * @var array
     */
    protected $metadata = array();

    /**
     * Should be either MessageHeader::TYPE_COMMAND or MessageHeader::TYPE_EVENT
     *
     * @return string
     */
    abstract protected function messageType();

    /**
     * Creates a new domain message from given array
     *
     * @param array $messageData
     * @return static
     */
    public static function fromArray(array $messageData)
    {
        Assertion::keyExists($messageData, 'uuid');
        Assertion::keyExists($messageData, 'name');
        Assertion::keyExists($messageData, 'version');
        Assertion::keyExists($messageData, 'payload');
        Assertion::keyExists($messageData, 'metadata');
        Assertion::keyExists($messageData, 'created_at');
        Assertion::isArray($messageData['metadata']);

        return new static(
            $messageData['name'],
            $messageData['payload'],
            $messageData['version'],
            Uuid::fromString($messageData['uuid']),
            \DateTimeImmutable::createFromFormat(\DateTime::ISO8601, $messageData['created_at']),
            $messageData['metadata']
        );
    }

    /**
     * Creates a new domain message from given RemoteMessage
     *
     * @param RemoteMessage $message
     * @return static
     */
    public static function fromRemoteMessage(RemoteMessage $message)
    {
        return new static(
            $message->name(),
            $message->payload(),
            $message->header()->version(),
            $message->header()->uuid(),
            $message->header()->createdAt(),
            $message->header()->metadata()
        );
    }

    /**
     * We force implementors to provide a meaningful factory method or use the fromArray or fromRemoteMessage methods
     *
     * @param string $messageName
     * @param null $payload
     * @param int $version
     * @param Uuid $uuid
     * @param \DateTimeImmutable $createdAt
     * @param array $metadata
     * @throws \RuntimeException
     */
    protected function __construct($messageName, $payload = null, $version = 1, Uuid $uuid = null, \DateTimeImmutable $createdAt = null, array $metadata = [])
    {
        $this->name = $messageName;

        if (!is_null($payload)) {

            if (! is_array($payload)) {
                $payload = $this->convertPayload($payload);
            }

            if (is_array($payload)) {
                $this->payload = $payload;
            } else {
                throw new \RuntimeException(
                    sprintf(
                        "Payload must be an array"
                        . "instance of %s given.",
                        ((is_object($payload)? get_class($payload) : gettype($payload)))
                    )
                );
            }
        }

        $this->version = $version;

        if (is_null($uuid)) {
            $uuid = Uuid::uuid4();
        }

        $this->uuid = $uuid;

        if (is_null($createdAt)) {
            $createdAt = new \DateTimeImmutable();
        }

        $this->createdAt = $createdAt;

        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return $this->payload;
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
            'name' => $this->name,
            'uuid' => $this->uuid->toString(),
            'version' => $this->version,
            'payload' => $this->payload,
            'metadata' => $this->metadata,
            'created_at' => $this->createdAt()->format(\DateTime::ISO8601)
        ];
    }

    /**
     * @return RemoteMessage
     */
    public function toRemoteMessage()
    {
        $messageHeader = new MessageHeader($this->uuid, $this->createdAt, $this->version, $this->messageType(), $this->metadata);

        return new RemoteMessage($this->name, $messageHeader, $this->payload);
    }

    /**
     * @return string Name of the message
     */
    public function messageName()
    {
        return $this->name;
    }

    /**
     * Hook point for extending classes, override this method to convert payload to array
     *
     * @param mixed $payload
     * @return mixed
     */
    protected function convertPayload($payload)
    {
        return $payload;
    }
} 