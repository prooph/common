<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 7/26/15 - 3:30 PM
 */
namespace Prooph\Common\Messaging;
use Assert\Assertion;

/**
 * Class NoOpMessageConverter
 *
 * The NoOpMessageConverter does not perform any conversion logic.
 * It simply returns DomainMessage::toArray.
 * The converter acts as a default implementation but allows replacement
 * with a custom converter using some special logic.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class NoOpMessageConverter implements MessageConverter
{

    /**
     * @param Message $domainMessage
     * @return array
     */
    public function convertToArray(Message $domainMessage)
    {
        Assertion::isInstanceOf($domainMessage, DomainMessage::class);

        return $domainMessage->toArray();
    }
}