<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 08.03.14 - 21:03
 */

namespace Prooph\Common\Messaging;

/**
 * Class Command
 *
 * This is the base class for commands used to trigger actions in a domain model.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
abstract class Command extends DomainMessage
{
    /**
     * @return string
     */
    public function messageType()
    {
        return self::TYPE_COMMAND;
    }
}
