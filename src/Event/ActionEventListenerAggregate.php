<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 3/5/15 - 6:52 PM
 */

namespace Prooph\Common\Event;

/**
 * Interface ActionEventListenerAggregate
 *
 * An action event listener aggregate interface can itself attach to an ActionEventEmitter.
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ActionEventListenerAggregate
{
    /**
     * @param ActionEventEmitter $dispatcher
     */
    public function attach(ActionEventEmitter $dispatcher);

    /**
     * @param ActionEventEmitter $dispatcher
     */
    public function detach(ActionEventEmitter $dispatcher);
}
