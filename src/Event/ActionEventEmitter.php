<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 3/5/15 - 6:12 PM
 */

namespace Prooph\Common\Event;

/**
 * Interface ActionEventEmitter
 *
 * An action event dispatcher dispatches ActionEvents which are mutable objects used as a communication mechanism
 * between ActionEventListeners listening on the same event and performing actions based on it.
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface ActionEventEmitter
{
    /**
     * @param null|string $name of the action event
     * @param string|object $target of the action event
     * @param null|array|\ArrayAccess $params with which the event is initialized
     * @return ActionEvent that can be triggered by the ActionEventEmitter
     */
    public function getNewActionEvent($name = null, $target = null, $params = null);

    /**
     * Trigger an action event
     *
     * @param \Prooph\Common\Event\ActionEvent $event
     */
    public function dispatch(ActionEvent $event);

    /**
     * Trigger an event until the given callback returns a boolean true
     *
     * The callback is invoked after each listener and gets the action event as only argument
     *
     * @param \Prooph\Common\Event\ActionEvent $event
     * @param  callable $callback
     */
    public function dispatchUntil(ActionEvent $event, $callback);

    /**
     * Attach a listener to an event
     *
     * @param  string $event
     * @param  callable|ActionEventListener $listener
     * @param  int $priority Priority at which to register listener
     * @return ListenerHandler
     */
    public function attachListener($event, $listener, $priority = 1);

    /**
     * Detach an event listener
     *
     * @param ListenerHandler $listenerHandler
     * @return bool
     */
    public function detachListener(ListenerHandler $listenerHandler);

    /**
     * Attach a listener aggregate
     *
     * @param  ActionEventListenerAggregate $aggregate
     */
    public function attachListenerAggregate(ActionEventListenerAggregate $aggregate);

    /**
     * Detach a listener aggregate
     *
     * @param  ActionEventListenerAggregate $aggregate
     */
    public function detachListenerAggregate(ActionEventListenerAggregate $aggregate);
}
