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

namespace Prooph\Common\Event;

/**
 * Interface ActionEventListenerAggregate
 *
 * An action event listener aggregate interface can itself attach to an ActionEventEmitter.
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface ActionEventListenerAggregate
{
    public function attach(ActionEventEmitter $dispatcher): void;

    public function detach(ActionEventEmitter $dispatcher): void;
}
