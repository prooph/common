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

namespace Prooph\Common\Event;

/**
 * An action event listener aggregate interface can itself attach to an ActionEventEmitter.
 */
interface ActionEventListenerAggregate
{
    public function attach(ActionEventEmitter $dispatcher): void;

    public function detach(ActionEventEmitter $dispatcher): void;
}
