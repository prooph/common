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

class DefaultActionEvent implements ActionEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $target;

    /**
     * @var array|\ArrayAccess
     */
    protected $params;

    /**
     * @var bool
     */
    protected $stopPropagation = false;

    /**
     * @param string $name
     * @param null|string|object $target
     * @param array|\ArrayAccess|null $params
     */
    public function __construct(string $name, $target = null, $params = null)
    {
        $this->setName($name);

        $this->setTarget($target);

        if ($params === null) {
            $params = [];
        }

        $this->setParams($params);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|object
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get parameters passed to the event
     *
     * @return array|\ArrayAccess
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get a single parameter by name
     *
     * @param  string $name
     * @param  mixed $default Default value to return if parameter does not exist
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        return $this->params[$name] ?? $default;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Set the event target/context
     *
     * @param  null|string|object $target
     *
     * @return void
     */
    public function setTarget($target): void
    {
        $this->target = $target;
    }

    /**
     * Set event parameters
     *
     * @param  array|\ArrayAccess $params
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function setParams($params): void
    {
        if (! \is_array($params) && ! $params instanceof \ArrayAccess) {
            throw new \InvalidArgumentException('Event params are invalid. Expected type is array or \\ArrayAccess. Got ' . \gettype($params));
        }

        $this->params = $params;
    }

    /**
     * Set a single parameter by key
     *
     * @param  string $name
     * @param  mixed $value
     *
     * @return void
     */
    public function setParam(string $name, $value): void
    {
        $this->params[$name] = $value;
    }

    /**
     * Indicate whether or not the parent ActionEventEmitter should stop propagating events
     */
    public function stopPropagation(bool $flag = true): void
    {
        $this->stopPropagation = $flag;
    }

    /**
     * Has this event indicated event propagation should stop?
     */
    public function propagationIsStopped(): bool
    {
        return $this->stopPropagation;
    }
}
