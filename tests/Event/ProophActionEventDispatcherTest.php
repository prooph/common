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
use Prooph\Common\Event\ProophActionEventDispatcher;
use ProophTest\Common\Mock\ActionEventListenerMock;
use ProophTest\Common\Mock\ActionListenerAggregateMock;

class ProophActionEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProophActionEventDispatcher
     */
    private $proophActionEventDispatcher;

    protected function setUp()
    {
        $this->proophActionEventDispatcher = new ProophActionEventDispatcher();
    }

    /**
     * @test
     */
    function it_attaches_action_event_listeners_and_dispatch_event_to_them()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventDispatcher->attachListener("test", $listener1);
        $this->proophActionEventDispatcher->attachListener("test", $listener2);

        $this->proophActionEventDispatcher->dispatch($actionEvent);

        $this->assertSame($lastEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    function it_detaches_a_listener()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $handler = $this->proophActionEventDispatcher->attachListener("test", $listener1);
        $this->proophActionEventDispatcher->attachListener("test", $listener2);

        $this->proophActionEventDispatcher->detachListener($handler);

        $this->proophActionEventDispatcher->dispatch($actionEvent);

        $this->assertNull($listener1->lastEvent);
        $this->assertSame($actionEvent, $lastEvent);
    }

    /**
     * @test
     */
    function it_triggers_listeners_until_callback_returns_true()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventDispatcher->attachListener("test", $listener1);
        $this->proophActionEventDispatcher->attachListener("test", $listener2);

        $this->proophActionEventDispatcher->dispatchUntil($actionEvent, function (ActionEvent $e) {
            //We return true directly after first listener was triggered
            return true;
        });

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    function it_stops_dispatching_when_event_propagation_is_stopped()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) { $event->stopPropagation(true); };
        $listener3 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventDispatcher->attachListener("test", $listener1);
        $this->proophActionEventDispatcher->attachListener("test", $listener2);
        $this->proophActionEventDispatcher->attachListener("test", $listener3);

        $this->proophActionEventDispatcher->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    function it_triggers_listeners_with_high_priority_first()
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) { $event->stopPropagation(true); };
        $listener3 = function (ActionEvent $event) use (&$lastEvent) {
            if ($event->getParam('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->proophActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventDispatcher->attachListener("test", $listener1, -100);
        $this->proophActionEventDispatcher->attachListener("test", $listener3);
        $this->proophActionEventDispatcher->attachListener("test", $listener2, 100);

        $this->proophActionEventDispatcher->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertNull($listener1->lastEvent);
    }

    /**
     * @test
     */
    function it_attaches_a_listener_aggregate()
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->proophActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventDispatcher->attachListener("test", $listener1);
        $this->proophActionEventDispatcher->attachListenerAggregate($listenerAggregate);

        $this->proophActionEventDispatcher->dispatch($actionEvent);

        //The listener aggregate attaches itself with a high priority and stops event propagation so $listener1 should not be triggered
        $this->assertNull($listener1->lastEvent);
    }

    /**
     * @test
     */
    function it_detaches_listener_aggregate()
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->proophActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->proophActionEventDispatcher->attachListener("test", $listener1);
        $this->proophActionEventDispatcher->attachListenerAggregate($listenerAggregate);
        $this->proophActionEventDispatcher->detachListenerAggregate($listenerAggregate);

        $this->proophActionEventDispatcher->dispatch($actionEvent);

        //If aggregate is not detached it would stop the event propagation and $listener1 would not be triggered
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }
} 