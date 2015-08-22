<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 5/22/15 - 6:59 PM
 */
namespace Prooph\Common\Event;

/**
 * Class DefaultActionEvent
 *
 * Default implementation of ActionEvent
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
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
     * @var boolean
     */
    protected $stopPropagation = false;

    /**
     * @param string $name
     * @param mixed|null $target
     * @param array|\ArrayAccess|null $params
     */
    public function __construct($name, $target = null, $params = null)
    {
        $this->setName($name);

        $this->setTarget($target);

        if ($params === null) {
            $params = [];
        }

        $this->setParams($params);
    }

    /**
     * Get event name
     *
     * @return string
     */
    public function getName()
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
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return isset($this->params[$name])? $this->params[$name] : $default;
    }

    /**
     * Set the event name
     *
     * @param  string $name
     * @throws \InvalidArgumentException
     * @return void
     */
    public function setName($name)
    {
        if (! is_string($name)) {
            throw new \InvalidArgumentException("Event name is invalid. Expected string. Got " . gettype($name));
        }

        $this->name = $name;
    }

    /**
     * Set the event target/context
     *
     * @param  null|string|object $target
     * @return void
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Set event parameters
     *
     * @param  array|\ArrayAccess $params
     * @throws \InvalidArgumentException
     * @return void
     */
    public function setParams($params)
    {
        if (! is_array($params) && ! $params instanceof \ArrayAccess) {
            throw new \InvalidArgumentException("Event params are invalid. Expected type is array or \\ArrayAccess. Got " . gettype($params));
        }

        $this->params = $params;
    }

    /**
     * Set a single parameter by key
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * Indicate whether or not the parent ActionEventEmitter should stop propagating events
     *
     * @param  bool $flag
     * @return void
     */
    public function stopPropagation($flag = true)
    {
        $this->stopPropagation = $flag;
    }

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function propagationIsStopped()
    {
        return $this->stopPropagation;
    }
}
