<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 7:46 PM
 */
namespace ProophTest\Common;
use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ZF2\Zf2ActionEvent;
use Prooph\Common\Event\ZF2\Zf2ActionEventDispatcher;
use ProophTest\Common\Mock\ActionEventListenerMock;
use ProophTest\Common\Mock\ActionListenerAggregateMock;

/**
 * Class Zf2ActionEventDispatcherTest
 *
 * @package ProophTest\Common
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Zf2ActionEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Zf2ActionEventDispatcher
     */
    private $zf2ActionEventDispatcher;

    protected function setUp()
    {
        $this->zf2ActionEventDispatcher = new Zf2ActionEventDispatcher();
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

        $actionEvent = $this->zf2ActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->zf2ActionEventDispatcher->attachListener("test", $listener1);
        $this->zf2ActionEventDispatcher->attachListener("test", $listener2);

        $this->zf2ActionEventDispatcher->dispatch($actionEvent);

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

        $actionEvent = $this->zf2ActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $handler = $this->zf2ActionEventDispatcher->attachListener("test", $listener1);
        $this->zf2ActionEventDispatcher->attachListener("test", $listener2);

        $this->zf2ActionEventDispatcher->detachListener($handler);

        $this->zf2ActionEventDispatcher->dispatch($actionEvent);

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

        $actionEvent = $this->zf2ActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->zf2ActionEventDispatcher->attachListener("test", $listener1);
        $this->zf2ActionEventDispatcher->attachListener("test", $listener2);

        $this->zf2ActionEventDispatcher->dispatchUntil($actionEvent, function (ActionEvent $e) {
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

        $actionEvent = $this->zf2ActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->zf2ActionEventDispatcher->attachListener("test", $listener1);
        $this->zf2ActionEventDispatcher->attachListener("test", $listener2);
        $this->zf2ActionEventDispatcher->attachListener("test", $listener3);

        $this->zf2ActionEventDispatcher->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    /**
     * @test
     */
    function it_attaches_a_listener_aggregate()
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->zf2ActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->zf2ActionEventDispatcher->attachListener("test", $listener1);
        $this->zf2ActionEventDispatcher->attachListenerAggregate($listenerAggregate);

        $this->zf2ActionEventDispatcher->dispatch($actionEvent);

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

        $actionEvent = $this->zf2ActionEventDispatcher->getNewActionEvent("test", $this, ['payload' => true]);

        $this->zf2ActionEventDispatcher->attachListener("test", $listener1);
        $this->zf2ActionEventDispatcher->attachListenerAggregate($listenerAggregate);
        $this->zf2ActionEventDispatcher->detachListenerAggregate($listenerAggregate);

        $this->zf2ActionEventDispatcher->dispatch($actionEvent);

        //If aggregate is not detached it would stop the event propagation and $listener1 would not be triggered
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }
} 