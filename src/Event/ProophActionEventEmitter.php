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

namespace Prooph\Common\Event;

use Assert\Assertion;

class ProophActionEventEmitter implements ActionEventEmitter
{
    /**
     * Map of event name to listeners array
     *
     * @var array
     */
    protected $events = [];

    /**
     * @var string[]
     */
    protected $availableEventNames = [];

    public function __construct(array $availableEventNames = [])
    {
        Assertion::allString($availableEventNames, 'Available event names must be an array of strings');
        $this->availableEventNames = $availableEventNames;
    }

    /**
     * @param null|string $name of the action event
     * @param string|object $target of the action event
     * @param null|array|\ArrayAccess $params with which the event is initialized
     *
     * @return ActionEvent that can be triggered by the ActionEventEmitter
     */
    public function getNewActionEvent(string $name = null, $target = null, $params = null): ActionEvent
    {
        if ($name === null) {
            $name = 'action_event';
        }

        return new DefaultActionEvent($name, $target, $params);
    }

    public function dispatch(ActionEvent $event): void
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
     */
    public function dispatchUntil(ActionEvent $event, callable $callback): void
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
     * @throws \InvalidArgumentException
     */
    public function attachListener(string $event, callable $listener, int $priority = 1): ListenerHandler
    {
        if (! empty($this->availableEventNames) && ! \in_array($event, $this->availableEventNames, true)) {
            throw new \InvalidArgumentException("Unknown event name given: $event");
        }

        $handler = new DefaultListenerHandler($listener);

        $this->events[$event][((int) $priority) . '.0'][] = $handler;

        return $handler;
    }

    public function detachListener(ListenerHandler $listenerHandler): bool
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

    public function attachListenerAggregate(ActionEventListenerAggregate $aggregate): void
    {
        $aggregate->attach($this);
    }

    public function detachListenerAggregate(ActionEventListenerAggregate $aggregate): void
    {
        $aggregate->detach($this);
    }

    /**
     * @param ActionEvent $event
     *
     * @return ListenerHandler[]
     */
    private function getListeners(ActionEvent $event): iterable
    {
        $prioritizedListeners = $this->events[$event->getName()] ?? [];

        \krsort($prioritizedListeners, SORT_NUMERIC);

        foreach ($prioritizedListeners as $listenersByPriority) {
            foreach ($listenersByPriority as $listenerHandler) {
                yield $listenerHandler;
            }
        }
    }
}
