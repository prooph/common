<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 8/11/15 - 10:04 PM
 */

namespace Prooph\Common\Messaging;

use Ramsey\Uuid\Uuid;

/**
 * Interface Message
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface Message extends HasMessageName
{
    const TYPE_COMMAND = 'command';
    const TYPE_EVENT   = 'event';
    const TYPE_QUERY   = 'query';

    /**
     * Should be one of Message::TYPE_COMMAND, Message::TYPE_EVENT or Message::TYPE_QUERY
     *
     * @return string
     */
    public function messageType();

    /**
     * @return Uuid
     */
    public function uuid();

    /**
     * @return int
     */
    public function version();

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt();

    /**
     * @return array
     */
    public function metadata();

    /**
     * Returns a new instance of the message with given version
     *
     * @param int $version
     * @return Message
     */
    public function withVersion($version);

    /**
     * Returns a new instance of the message with given metadata
     *
     * Metadata must be given as a hash table containing only scalar values
     *
     * @param array $metadata
     * @return Message
     */
    public function withMetadata(array $metadata);

    /**
     * Returns new instance of message with $key => $value added to metadata
     *
     * Given value must have a scalar type.
     *
     * @param string $key
     * @param mixed $value
     * @return Message
     */
    public function withAddedMetadata($key, $value);
}
