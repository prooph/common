<?php

/**
 * This file is part of prooph/common.
 * (c) 2014-2022 Alexander Miertsch <kontakt@codeliner.ws>
 * (c) 2015-2022 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Messaging;

/**
 * This is the base class for domain events.
 */
abstract class DomainEvent extends DomainMessage
{
    public function messageType(): string
    {
        return self::TYPE_EVENT;
    }
}
