<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 5/1/15 - 1:44 PM
 */
namespace Prooph\Common\Messaging;

/**
 * Class DomainEvent
 *
 * This is the base class for domain events.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
abstract class DomainEvent extends DomainMessage
{
    /**
     * @return string
     */
    public function messageType()
    {
        return self::TYPE_EVENT;
    }
}
