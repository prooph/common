<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.03.14 - 18:22
 */

namespace Prooph\Common\Messaging;

use Assert\Assertion;
use Rhumsaa\Uuid\Uuid;

/**
 * Class MessageHeader
 *
 * The message header encapsulates meta information of a RemoteMessage.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class MessageHeader
{
    const TYPE_COMMAND = 'command';
    const TYPE_EVENT   = 'event';
    const TYPE_QUERY   = 'query';

    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var int
     */
    private $version;

    /**
     * Type of the Message, can either be command or event
     *
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $metadata;

    /**
     * @param array $messageHeaderArray
     * @return MessageHeader
     */
    public static function fromArray(array $messageHeaderArray)
    {
        Assertion::keyExists($messageHeaderArray, 'uuid');
        Assertion::keyExists($messageHeaderArray, 'created_at');
        Assertion::keyExists($messageHeaderArray, 'version');
        Assertion::keyExists($messageHeaderArray, 'type');
        if (isset($messageHeaderArray['metadata'])) {
            Assertion::isArray($messageHeaderArray['metadata']);
        } else {
            $messageHeaderArray['metadata'] = [];
        }

        $uuid = Uuid::fromString($messageHeaderArray['uuid']);
        $createdAt = \DateTimeImmutable::createFromFormat(\DateTime::ISO8601, $messageHeaderArray['created_at']);

        return new static(
            $uuid,
            $createdAt,
            $messageHeaderArray['version'],
            $messageHeaderArray['type'],
            $messageHeaderArray['metadata']
        );
    }

    /**
     * @param Uuid $uuid
     * @param \DateTimeImmutable $createdAt
     * @param int $version
     * @param string $type
     * @param array $metadata
     */
    public function __construct(Uuid $uuid, \DateTimeImmutable $createdAt, $version, $type, array $metadata = [])
    {
        Assertion::notEmpty($version, 'MessageHeader.version must not be empty');
        Assertion::integer($version, 'MessageHeader.version must be an integer');

        Assertion::inArray($type, [self::TYPE_COMMAND, self::TYPE_EVENT, self::TYPE_QUERY], 'MessageHeader.type must be command, query or event');

        $this->uuid      = $uuid;
        $this->createdAt = $createdAt;
        $this->version   = $version;
        $this->type      = $type;
        $this->metadata  = $metadata;
    }

    /**
     * @return Uuid
     */
    public function uuid()
    {
        return $this->uuid;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function metadata()
    {
        return $this->metadata;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'uuid'      => $this->uuid()->toString(),
            'created_at' => $this->createdAt()->format(\DateTime::ISO8601),
            'version'   => $this->version(),
            'type'      => $this->type(),
            'metadata'  => $this->metadata()
        );
    }
}
