<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 5/22/15 - 8:54 PM
 */
namespace ProophTest\Common\Event\ZF2;

use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ListenerHandler;
use Prooph\Common\Event\ProophActionEventEmitter;
use ProophTest\Common\Mock\ActionEventListenerMock;
use ProophTest\Common\Mock\ActionListenerAggregateMock;

class ProophActionEventEmitterTest extends \PHPUnit_Framework_TestCase
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
    public function it_attaches_action_event_listeners_and_dispatch_event_to_them()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener("test", $listener1);
        $this->proophActionEventEmitter->attachListener("test", $listener2);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertSame($lastEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_detaches_a_listener()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent("test", $this, ['payload' => true]);

        $handler = $this->proophActionEventEmitter->attachListener("test", $listener1);
        $this->proophActionEventEmitter->attachListener("test", $listener2);

        $this->proophActionEventEmitter->detachListener($handler);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertNull($listener1->lastEvent);
        $this->assertSame($actionEvent, $lastEvent);
    }

    /**
     * @test
     */
    public function it_triggers_listeners_until_callback_returns_true()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener("test", $listener1);
        $this->proophActionEventEmitter->attachListener("test", $listener2);

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
    public function it_stops_dispatching_when_event_propagation_is_stopped()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) { $event->stopPropagation(true); };
        $listener3 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener("test", $listener1);
        $this->proophActionEventEmitter->attachListener("test", $listener2);
        $this->proophActionEventEmitter->attachListener("test", $listener3);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_triggers_listeners_with_high_priority_first()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) { $event->stopPropagation(true); };
        $listener3 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener("test", $listener1, -100);
        $this->proophActionEventEmitter->attachListener("test", $listener3);
        $this->proophActionEventEmitter->attachListener("test", $listener2, 100);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertNull($listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_attaches_a_listener_aggregate()
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener("test", $listener1);
        $this->proophActionEventEmitter->attachListenerAggregate($listenerAggregate);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        //The listener aggregate attaches itself with a high priority and stops event propagation so $listener1 should not be triggered
        $this->assertNull($listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_detaches_listener_aggregate()
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->proophActionEventEmitter->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventEmitter->attachListener("test", $listener1);
        $this->proophActionEventEmitter->attachListenerAggregate($listenerAggregate);
        $this->proophActionEventEmitter->detachListenerAggregate($listenerAggregate);

        $this->proophActionEventEmitter->dispatch($actionEvent);

        //If aggregate is not detached it would stop the event propagation and $listener1 would not be triggered
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    public function it_uses_default_event_name_if_none_given()
    {
        $event = $this->proophActionEventEmitter->getNewActionEvent();
        $this->assertEquals('action_event', $event->getName());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Given parameter event should be a string.
     */
    public function it_throws_exception_when_event_is_not_a_string_when_attaching_listener()
    {
        $this->proophActionEventEmitter->attachListener(null, null);
    }

    /**
     * @test
     */
    public function it_returns_false_when_unattached_listener_handler_gets_detached()
    {
        $listener = $this->getMockForAbstractClass(ListenerHandler::class);

        $this->assertFalse($this->proophActionEventEmitter->detachListener($listener));
    }

    /**
     * @test
     */
    public function it_returns_nothing_on_dispatch_until_when_no_listeners_attached()
    {
        $actionEventMock = $this->getMock(ActionEvent::class);

        $this->assertNull($this->proophActionEventEmitter->dispatchUntil($actionEventMock, function () {
            return true;
        }));
    }
}
