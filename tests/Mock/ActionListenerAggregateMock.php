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

namespace ProophTest\Common\Mock;

use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ActionEventEmitter;
use Prooph\Common\Event\ActionEventListenerAggregate;
use Prooph\Common\Event\DetachAggregateHandlers;

/**
 * Class ActionListenerAggregateMock
 *
 * @package ProophTest\Common\Mock
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class ActionListenerAggregateMock implements ActionEventListenerAggregate
{
    use DetachAggregateHandlers;

    /**
     * @param ActionEventEmitter $dispatcher
     */
    public function attach(ActionEventEmitter $dispatcher): void
    {
        $this->trackHandler($dispatcher->attachListener("test", [$this, "onTest"], 100));
    }

    /**
     * @param ActionEvent $event
     */
    public function onTest(ActionEvent $event): void
    {
        $event->stopPropagation(true);
    }
}
