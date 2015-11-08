<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 3/5/15 - 6:17 PM
 */

namespace Prooph\Common\Event;

/**
 * Interface ActionEvent
 *
 * An action event is mutable object used as a communication mechanism for ActionEventListeners listening on the same
 * event and performing actions based on the event and its current state.
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface ActionEvent
{
    /**
     * Get event name
     *
     * @return string
     */
    public function getName();

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|object
     */
    public function getTarget();

    /**
     * Get parameters passed to the event
     *
     * @return array|\ArrayAccess
     */
    public function getParams();

    /**
     * Get a single parameter by name
     *
     * @param  string $name
     * @param  mixed $default Default value to return if parameter does not exist
     * @return mixed
     */
    public function getParam($name, $default = null);

    /**
     * Set the event name
     *
     * @param  string $name
     * @return void
     */
    public function setName($name);

    /**
     * Set the event target/context
     *
     * @param  null|string|object $target
     * @return void
     */
    public function setTarget($target);

    /**
     * Set event parameters
     *
     * @param  array|\ArrayAccess $params
     * @return void
     */
    public function setParams($params);

    /**
     * Set a single parameter by key
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function setParam($name, $value);

    /**
     * Indicate whether or not the parent ActionEventEmitter should stop propagating events
     *
     * @param  bool $flag
     * @return void
     */
    public function stopPropagation($flag = true);

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function propagationIsStopped();
}
