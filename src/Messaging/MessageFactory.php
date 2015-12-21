<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 7/26/15 - 12:08 AM
 */

namespace Prooph\Common\Messaging;

/**
 * Interface MessageFactory
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface MessageFactory
{
    /**
     * Message data MUST contain at least a "payload" key
     * but may also contain "uuid", "message_name", "version", "metadata", and "created_at".
     *
     * In general the message factory MUST support creating event objects from an array returned by
     * the corresponding Prooph\Common\Messaging\MessageConverter
     *
     * You can use the assertion helper Prooph\Common\Messaging\MessageDataAssertion to assert message data
     * before processing it.
     *
     * If one of the optional keys is not part of the message data the factory should use a default instead:
     * For example:
     * uuid = (new \Prooph\Common\Uuid\Version4Generator())->generate()
     * message_name = $messageName //First parameter passed to the method
     * version = 1
     * metadata = []
     * created_at = new \DateTimeImmutable('now', new \DateTimeZone('UTC')) //We treat all dates as UTC
     *
     * @param string $messageName
     * @param array $messageData
     * @return Message
     */
    public function createMessageFromArray($messageName, array $messageData);
}
