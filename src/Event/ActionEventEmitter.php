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
    public function getNewActionEvent(?string $name, $target = null, $params = null) : ActionEvent;

    public function dispatch(ActionEvent $event) : void;

    /**
     * Trigger an event until the given callback returns a boolean true
     *
     * The callback is invoked after each listener and gets the action event as only argument
     */
    public function dispatchUntil(ActionEvent $event, callable $callback) : void;

    /**
     * Attach a listener to an event
     *
     * @param  string $event
     * @param  callable $listener
     * @param  int $priority Priority at which to register listener
     * @return ListenerHandler
     */
    public function attachListener(string $event, callable $listener, int $priority = 1) : ListenerHandler;

    public function detachListener(ListenerHandler $listenerHandler) : bool;

    public function attachListenerAggregate(ActionEventListenerAggregate $aggregate) : void;

    public function detachListenerAggregate(ActionEventListenerAggregate $aggregate) : void;
}
