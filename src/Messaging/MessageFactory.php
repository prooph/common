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
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface MessageFactory
{
    /**
     * Message data MUST contain at least a "payload" key
     * but may also contain "uuid", "message_name", "version", "metadata", and "created_at".
     *
     * If one of the optional keys is not part of the message data the factory should use a default instead:
     * For example:
     * uuid = Uuid::uuid4()
     * message_name = $messageName //First parameter passed to the method
     * version = 1
     * metadata = []
     * created_at = new \DateTimeImmutable()
     *
     * @param string $messageName
     * @param array $messageData
     * @return Message
     */
    public function createMessageFromArray($messageName, array $messageData);
}
