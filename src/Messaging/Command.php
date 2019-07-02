<?php

/**
 * This file is part of prooph/common.
 * (c) 2014-2019 Alexander Miertsch <kontakt@codeliner.ws>
 * (c) 2015-2019 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Messaging;

/**
 * This is the base class for commands used to trigger actions in a domain model.
 */
abstract class Command extends DomainMessage
{
    public function messageType(): string
    {
        return self::TYPE_COMMAND;
    }
}
