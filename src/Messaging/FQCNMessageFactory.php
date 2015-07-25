<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 7/26/15 - 12:11 AM
 */
namespace Prooph\Common\Messaging;
use Assert\Assertion;

/**
 * Class FQCNMessageFactory
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class FQCNMessageFactory implements MessageFactory
{
    /**
     * @param string $messageName
     * @param array $messageData
     * @throws \UnexpectedValueException
     * @return DomainMessage
     */
    public function createMessageFromArray($messageName, array $messageData)
    {
        if (! class_exists($messageName)) {
            throw new \UnexpectedValueException('Given message name is not a valid class: ' . (string)$messageName);
        }

        $ref = new \ReflectionClass($messageName);

        if (!$ref->isSubclassOf(DomainMessage::class)) {
            throw new \UnexpectedValueException(sprintf(
                'Message class %s is not a sub class of %s',
                $messageName,
                DomainMessage::class
            ));
        }

        $messageData['name'] = $messageName;

        return $messageName::fromArray($messageData);
    }
}