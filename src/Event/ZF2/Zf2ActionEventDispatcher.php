<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 7:18 PM
 */
namespace Prooph\Common\Event\ZF2;

use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ActionEventDispatcher;
use Prooph\Common\Event\ActionEventListener;
use Prooph\Common\Event\ActionEventListenerAggregate;
use Prooph\Common\Event\ListenerHandler;
use Zend\EventManager\EventManager;

/**
 * Class Zf2ActionEventDispatcher
 *
 * @package Prooph\Common\Event\ZF2
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Zf2ActionEventDispatcher extends EventManager implements ActionEventDispatcher
{
    /**
     * @param ActionEvent $event
     * @return void
     */
    public function dispatch(ActionEvent $event)
    {
        if (! $event instanceof Zf2ActionEvent) {
            $event = $this->toZf2Event($event);
        }

        parent::trigger($event);
    }

    /**
     * @param ActionEvent $event
     * @param callable|object|string $callback
     * @return void
     */
    public function dispatchUntil(ActionEvent $event, $callback)
    {
        if (! $event instanceof Zf2ActionEvent) {
            $event = $this->toZf2Event($event);
        }

        parent::trigger($event, function() use ($event, $callback) {
            return $callback($event);
        });
    }

    /**
     * Attach a listener to an event
     *
     * @param  string $event
     * @param  callable|ActionEventListener $listener
     * @param  int $priority Priority at which to register listener
     * @return ListenerHandler
     */
    public function attachListener($event, $listener, $priority = 1)
    {
        $callbackHandler = parent::attach($event, $listener, $priority);

        return new Zf2ActionEventListenerHandler($callbackHandler);
    }

    /**
     * Detach an event listener
     *
     * @param ListenerHandler $listenerHandler
     * @return void
     */
    public function detachListener(ListenerHandler $listenerHandler)
    {
        if ($listenerHandler instanceof Zf2ActionEventListenerHandler) {
            parent::detach($listenerHandler->getCallbackHandler());
        }
    }

    /**
     * Attach a listener aggregate
     *
     * @param  ActionEventListenerAggregate $aggregate
     * @return void
     */
    public function attachListenerAggregate(ActionEventListenerAggregate $aggregate)
    {
        $aggregate->attach($this);
    }

    /**
     * Detach a listener aggregate
     *
     * @param  ActionEventListenerAggregate $aggregate
     * @return void
     */
    public function detachListenerAggregate(ActionEventListenerAggregate $aggregate)
    {
        $aggregate->detach($this);
    }

    /**
     * @param ActionEvent $event
     * @return Zf2ActionEvent
     */
    private function toZf2Event(ActionEvent $event)
    {
        return new Zf2ActionEvent($event->getName(), $event->getTarget(), $event->getParams());
    }

    /**
     * @param null|string $name of the action event
     * @param null|string|object $target of the action event
     * @param null|array|\ArrayAccess $params with which the event is initialized
     * @return ActionEvent that can be triggered by the ActionEventDispatcher
     */
    public function getNewActionEvent($name = null, $target = null, $params = null)
    {
        return new Zf2ActionEvent($name, $target, $params);
    }
}