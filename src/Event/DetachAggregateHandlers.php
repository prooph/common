<?php
/**
 * This file is part of the prooph/common.
 *  (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 *  (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare (strict_types=1);

namespace Prooph\Common\Event;

/**
 * Trait DetachAggregateHandlers
 *
 * Trait to centralize logic of keeping track of registered ListenerHandlers of a ActionEventListenerAggregate and
 * to simplify detaching a ActionEventListenerAggregate.
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <contact@prooph.de>
 */
trait DetachAggregateHandlers
{
    /**
     * @var ListenerHandler[]
     */
    private $handlerCollection = [];

    protected function trackHandler(ListenerHandler $handler)
    {
        $this->handlerCollection[] = $handler;
    }

    /**
     * @param ActionEventEmitter $dispatcher
     */
    public function detach(ActionEventEmitter $dispatcher)
    {
        foreach ($this->handlerCollection as $handler) {
            $dispatcher->detachListener($handler);
        }
    }
}
