<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 8:25 PM
 */
namespace ProophTest\Common\Mock;

use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ActionEventDispatcher;
use Prooph\Common\Event\ActionEventListenerAggregate;
use Prooph\Common\Event\DetachAggregateHandlers;

/**
 * Class ActionListenerAggregateMock
 *
 * @package ProophTest\Common\Mock
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ActionListenerAggregateMock implements ActionEventListenerAggregate
{
    use DetachAggregateHandlers;

    /**
     * @param ActionEventDispatcher $dispatcher
     */
    public function attach(ActionEventDispatcher $dispatcher)
    {
        $this->trackHandler($dispatcher->attachListener("test", [$this, "onTest"], 100));
    }

    /**
     * @param ActionEvent $event
     */
    public function onTest(ActionEvent $event)
    {
        $event->stopPropagation(true);
    }
}