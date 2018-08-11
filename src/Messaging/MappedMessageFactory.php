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

use DateTimeImmutable;
use DateTimeZone;
use Ramsey\Uuid\Uuid;

final class MappedMessageFactory implements MessageFactory
{
    /** @var array */
    protected $messageMap = [];

    // key = event-type, value = aggregate-root-class
    public function __construct(array $messageMap)
    {
        if (empty($messageMap)) {
            throw new \InvalidArgumentException('Map cannot be empty');
        }

        foreach ($messageMap as $type => $class) {
            if (! \is_string($type) || empty($type)) {
                throw new \InvalidArgumentException('Type must be a non-empty string');
            }

            if (! \is_string($class) || empty($class)) {
                throw new \InvalidArgumentException('Class must be a non-empty string');
            }

            $this->messageMap[$type] = $class;
        }
    }

    public function createMessageFromArray(array $messageData): Message
    {
        if (! isset($messageData['message_name'])) {
            throw new \InvalidArgumentException('Key "message_name" is missing in message data');
        }

        $messageName = $messageData['message_name'];

        if (! \is_string($messageName) || empty($messageName)) {
            throw new \InvalidArgumentException('Message name must be a non-empty string');
        }

        if (! isset($this->messageMap[$messageName])) {
            throw new \InvalidArgumentException('Given message name is unknown to this factory: ' . $messageName);
        }

        $class = $this->messageMap[$messageName];

        if (! \is_subclass_of($class, DomainMessage::class)) {
            throw new \UnexpectedValueException(\sprintf(
                'Message class %s is not a sub class of %s',
                $class,
                DomainMessage::class
            ));
        }

        if (! isset($messageData['uuid'])) {
            $messageData['uuid'] = Uuid::uuid4()->toString();
        }

        if (! isset($messageData['created_at'])) {
            $messageData['created_at'] = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.uP');
        }

        if (! isset($messageData['payload'])) {
            $messageData['payload'] = [];
        }

        if (! isset($messageData['metadata'])) {
            $messageData['metadata'] = [];
        }

        return $class::fromArray($messageData);
    }
}
