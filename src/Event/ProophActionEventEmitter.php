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
 * Class ProophActionEventEmitter
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <contact@prooph.de>
 */
class ProophActionEventEmitter implements ActionEventEmitter
{
    /**
     * Map of event name to listeners array
     *
     * @var array
     */
    protected $events = [];

    /**
     * @param null|string $name of the action event
     * @param string|object $target of the action event
     * @param null|array|\ArrayAccess $params with which the event is initialized
     * @return ActionEvent that can be triggered by the ActionEventEmitter
     */
    public function getNewActionEvent($name = null, $target = null, $params = null)
    {
        if ($name === null) {
            $name = 'action_event';
        }

        return new DefaultActionEvent($name, $target, $params);
    }

    /**
     * Trigger an action event
     *
     * @param \Prooph\Common\Event\ActionEvent $event
     */
    public function dispatch(ActionEvent $event)
    {
        foreach ($this->getListeners($event) as $listenerHandler) {
            $listener = $listenerHandler->getActionEventListener();
            $listener($event);
            if ($event->propagationIsStopped()) {
                return;
            }
        }
    }

    /**
     * Trigger an event until the given callback returns a boolean true
     *
     * The callback is invoked after each listener and gets the action event as only argument
     *
     * @param \Prooph\Common\Event\ActionEvent $event
     * @param  callable $callback
     */
    public function dispatchUntil(ActionEvent $event, $callback)
    {
        foreach ($this->getListeners($event) as $listenerHandler) {
            $listener = $listenerHandler->getActionEventListener();
            $listener($event);

            if ($event->propagationIsStopped()) {
                return;
            }
            if ($callback($event) === true) {
                return;
            }
        }
    }

    /**
     * Attach a listener to an event
     *
     * @param  string $event Name of the event
     * @param  callable|ActionEventListener $listener
     * @param  int $priority Priority at which to register listener
     * @throws \InvalidArgumentException
     * @return ListenerHandler
     */
    public function attachListener($event, $listener, $priority = 1)
    {
        if (! is_string($event)) {
            throw new \InvalidArgumentException("Given parameter event should be a string. Got " . gettype($event));
        }

        $handler = new DefaultListenerHandler($listener);

        $this->events[$event][((int) $priority) . '.0'][] = $handler;

        return $handler;
    }

    /**
     * Detach an event listener
     *
     * @param ListenerHandler $listenerHandler
     * @return bool
     */
    public function detachListener(ListenerHandler $listenerHandler)
    {
        foreach ($this->events as &$prioritizedListeners) {
            foreach ($prioritizedListeners as &$listenerHandlers) {
                foreach ($listenerHandlers as $index => $listedListenerHandler) {
                    if ($listedListenerHandler === $listenerHandler) {
                        unset($listenerHandlers[$index]);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Attach a listener aggregate
     *
     * @param  ActionEventListenerAggregate $aggregate
     */
    public function attachListenerAggregate(ActionEventListenerAggregate $aggregate)
    {
        $aggregate->attach($this);
    }

    /**
     * Detach a listener aggregate
     *
     * @param  ActionEventListenerAggregate $aggregate
     */
    public function detachListenerAggregate(ActionEventListenerAggregate $aggregate)
    {
        $aggregate->detach($this);
    }

    /**
     * @param ActionEvent $event
     * @return ListenerHandler[]
     */
    private function getListeners(ActionEvent $event)
    {
        $prioritizedListeners = isset($this->events[$event->getName()])? $this->events[$event->getName()] : [] ;

        krsort($prioritizedListeners, SORT_NUMERIC);

        foreach ($prioritizedListeners as $listenersByPriority) {
            foreach ($listenersByPriority as $listenerHandler) {
                yield $listenerHandler;
            }
        }
    }
}
