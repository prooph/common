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

namespace ProophTest\Common\Mock;

use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ActionEventEmitter;
use Prooph\Common\Event\ActionEventListenerAggregate;
use Prooph\Common\Event\DetachAggregateHandlers;

final class ActionListenerAggregateMock implements ActionEventListenerAggregate
{
    use DetachAggregateHandlers;

    /**
     * @param ActionEventEmitter $dispatcher
     */
    public function attach(ActionEventEmitter $dispatcher): void
    {
        $callable = \Closure::fromCallable([$this, 'onTest']);
        $this->trackHandler($dispatcher->attachListener('test', $callable, 100));
    }

    /**
     * @param ActionEvent $event
     */
    private function onTest(ActionEvent $event): void
    {
        $event->stopPropagation(true);
    }
}
