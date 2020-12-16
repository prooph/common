<?php

/**
 * This file is part of prooph/common.
 * (c) 2014-2020 Alexander Miertsch <kontakt@codeliner.ws>
 * (c) 2015-2020 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Event;

/**
 * Trait to centralize logic of keeping track of registered ListenerHandlers of a ActionEventListenerAggregate and
 * to simplify detaching a ActionEventListenerAggregate.
 */
trait DetachAggregateHandlers
{
    /**
     * @var ListenerHandler[]
     */
    private $handlerCollection = [];

    protected function trackHandler(ListenerHandler $handler): void
    {
        $this->handlerCollection[] = $handler;
    }

    public function detach(ActionEventEmitter $dispatcher): void
    {
        foreach ($this->handlerCollection as $handler) {
            $dispatcher->detachListener($handler);
        }
    }
}
