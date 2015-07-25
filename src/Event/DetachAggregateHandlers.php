<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 8:34 PM
 */

namespace Prooph\Common\Event;

/**
 * Trait DetachAggregateHandlers
 *
 * Trait to centralize logic of keeping track of registered ListenerHandlers of a ActionEventListenerAggregate and
 * to simplify detaching a ActionEventListenerAggregate.
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
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
        foreach ($this->handlerCollection as $handler)
        {
            $dispatcher->detachListener($handler);
        }
    }
} 