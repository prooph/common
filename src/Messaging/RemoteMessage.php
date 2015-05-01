<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.03.14 - 18:12
 */

namespace Prooph\Common\Messaging;

use Assert\Assertion;

/**
 * Class RemoteMessage
 *
 * This class is a special type of a DTO. It has a MessageHeader which adds meta information to the remote message.
 * It is used by prooph components to communicate via remote channels like http or a messaging infrastructure.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class RemoteMessage
{
    /**
     * Name of the remote message
     *
     * @var string
     */
    private $name;

    /**
     * Meta information like uuid, version, type and custom metadata
     *
     * @var MessageHeader
     */
    private $header;

    /**
     * Data transported by this message
     *
     * Should only include scalar values and/or arrays because it is send over remote channels
     *
     * @var array
     */
    private $payload;

    /**
     * @param array $messageArray
     * @return RemoteMessage
     */
    public static function fromArray(array $messageArray)
    {
        Assertion::keyExists($messageArray, 'name');
        Assertion::keyExists($messageArray, 'header');
        Assertion::keyExists($messageArray, 'payload');

        $header = MessageHeader::fromArray($messageArray['header']);

        return new self($messageArray['name'], $header, $messageArray['payload']);
    }

    /**
     * @param string        $name
     * @param MessageHeader $messageHeader
     * @param array         $payload
     */
    public function __construct($name, MessageHeader $messageHeader, array $payload)
    {
        Assertion::notEmpty($name, 'Message.name should not be empty');
        Assertion::string($name, 'Message.name should be string');

        $this->name    = $name;
        $this->header  = $messageHeader;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return MessageHeader
     */
    public function header()
    {
        return $this->header;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'name'    => $this->name(),
            'header'  => $this->header()->toArray(),
            'payload' => $this->payload()
        );
    }
}
