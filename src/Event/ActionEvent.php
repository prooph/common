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

/**
 * An action event is mutable object used as a communication mechanism for ActionEventListeners listening on the same
 * event and performing actions based on the event and its current state.
 */
interface ActionEvent
{
    public function getName(): string;

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
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null);

    public function setName(string $name): void;

    /**
     * Set the event target/context
     *
     * @param  null|string|object $target
     *
     * @return void
     */
    public function setTarget($target): void;

    /**
     * Set event parameters
     *
     * @param  array|\ArrayAccess $params
     *
     * @return void
     */
    public function setParams($params): void;

    /**
     * Set a single parameter by key
     *
     * @param  string $name
     * @param  mixed $value
     *
     * @return void
     */
    public function setParam(string $name, $value): void;

    /**
     * Indicate whether or not the parent ActionEventEmitter should stop propagating events
     *
     * @param  bool $flag
     *
     * @return void
     */
    public function stopPropagation(bool $flag = true): void;

    /**
     * Has this event indicated event propagation should stop?
     */
    public function propagationIsStopped(): bool;
}
