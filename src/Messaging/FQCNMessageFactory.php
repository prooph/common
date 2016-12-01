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

use Ramsey\Uuid\Uuid;

/**
 * Class FQCNMessageFactory
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
class FQCNMessageFactory implements MessageFactory
{
    public function createMessageFromArray(string $messageName, array $messageData): Message
    {
        if (! class_exists($messageName)) {
            throw new \UnexpectedValueException('Given message name is not a valid class: ' . (string) $messageName);
        }

        if (! is_subclass_of($messageName, DomainMessage::class)) {
            throw new \UnexpectedValueException(sprintf(
                'Message class %s is not a sub class of %s',
                $messageName,
                DomainMessage::class
            ));
        }

        if (! isset($messageData['message_name'])) {
            $messageData['message_name'] = $messageName;
        }

        if (! isset($messageData['uuid'])) {
            $messageData['uuid'] = Uuid::uuid4();
        }

        if (! isset($messageData['created_at'])) {
            $time = (string) microtime(true);
            if (false === strpos($time, '.')) {
                $time .= '.0000';
            }
            $messageData['created_at'] = \DateTimeImmutable::createFromFormat('U.u', $time);
        }

        if (! isset($messageData['metadata'])) {
            $messageData['metadata'] = [];
        }

        return $messageName::fromArray($messageData);
    }
}
