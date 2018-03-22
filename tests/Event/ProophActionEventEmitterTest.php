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

namespace ProophTest\Common\Event;

use PHPUnit\Framework\TestCase;
use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ListenerHandler;
use Prooph\Common\Event\ProophActionEventEmitter;
use ProophTest\Common\Mock\ActionEventListenerMock;
use ProophTest\Common\Mock\ActionListenerAggregateMock;

class ProophActionEventEmitterTest extends TestCase
{
    /**
     * @var ProophActionEventEmitter
     */
    private $proophActionEventEmitter;

    protected function setUp()
    {
        $this->proophActionEventEmitter = new ProophActionEventEmitter();
    }

    /**
     * @test
     */
    public function it_attaches_action_event_listeners_and_dispatch_event_to_them(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener('test', $listener1);
        $this->proophActionEventEmitter->attachListener('test', $listener2);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertSame($lastEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_detaches_a_listener(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $handler = $this->proophActionEventEmitter->attachListener('test', $listener1);
        $this->proophActionEventEmitter->attachListener('test', $listener2);

        $this->proophActionEventEmitter->detachListener($handler);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertNull($listener1->lastEvent);
        $this->assertSame($actionEvent, $lastEvent);
    }

    /**
     * @test
     */
    public function it_triggers_listeners_until_callback_returns_true(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener('test', $listener1);
        $this->proophActionEventEmitter->attachListener('test', $listener2);

        $this->proophActionEventEmitter->dispatchUntil($actionEvent, function (ActionEvent $e) {
            //We return true directly after first listener was triggered
            return true;
        });

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_stops_dispatching_when_event_propagation_is_stopped(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) {
            $event->stopPropagation(true);
        };
        $listener3 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener('test', $listener1);
        $this->proophActionEventEmitter->attachListener('test', $listener2);
        $this->proophActionEventEmitter->attachListener('test', $listener3);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_stops_dispatching_when_event_propagation_is_stopped_2(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) {
        };
        $listener3 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener('test', $listener1);
        $this->proophActionEventEmitter->attachListener('test', $listener2);
        $this->proophActionEventEmitter->attachListener('test', $listener3);

        $this->proophActionEventEmitter->dispatchUntil($actionEvent, function (ActionEvent $e) {
            $e->stopPropagation(true);
        });

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_triggers_listeners_with_high_priority_first(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) {
            $event->stopPropagation(true);
        };
        $listener3 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener('test', $listener1, -100);
        $this->proophActionEventEmitter->attachListener('test', $listener3);
        $this->proophActionEventEmitter->attachListener('test', $listener2, 100);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertNull($listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_attaches_a_listener_aggregate(): void
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener('test', $listener1);
        $this->proophActionEventEmitter->attachListenerAggregate($listenerAggregate);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        //The listener aggregate attaches itself with a high priority and stops event propagation so $listener1 should not be triggered
        $this->assertNull($listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_detaches_listener_aggregate(): void
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener('test', $listener1);
        $this->proophActionEventEmitter->attachListenerAggregate($listenerAggregate);
        $this->proophActionEventEmitter->detachListenerAggregate($listenerAggregate);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        //If aggregate is not detached it would stop the event propagation and $listener1 would not be triggered
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_uses_default_event_name_if_none_given(): void
    {
        $event = $this->proophActionEventEmitter->getNewActionEvent();
        $this->assertEquals('action_event', $event->getName());
    }

    /**
     * @test
     */
    public function it_returns_false_when_unattached_listener_handler_gets_detached(): void
    {
        $listener = $this->getMockForAbstractClass(ListenerHandler::class);

        $this->assertFalse($this->proophActionEventEmitter->detachListener($listener));
    }

    /**
     * @test
     */
    public function it_dispatches_until_whith_no_listeners_attached(): void
    {
        $actionEventMock = $this->createMock(ActionEvent::class);

        $this->proophActionEventEmitter->dispatchUntil($actionEventMock, function () {
            return true;
        });
    }

    /**
     * @test
     */
    public function it_attaches_to_known_event_names(): void
    {
        $proophActionEventEmitter = new ProophActionEventEmitter(['foo']);
        $proophActionEventEmitter->attachListener('foo', function (): void {
        });
    }

    /**
     * @test
     */
    public function it_does_not_attach_to_unknown_event_names(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown event name given: invalid');

        $proophActionEventEmitter = new ProophActionEventEmitter(['foo']);
        $proophActionEventEmitter->attachListener('invalid', function (): void {
        });
    }
}
