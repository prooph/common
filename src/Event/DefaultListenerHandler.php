<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 5/22/15 - 8:27 PM
 */
namespace Prooph\Common\Event;

/**
 * Class DefaultListenerHandler
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DefaultListenerHandler implements ListenerHandler
{
    /**
     * @var callable|ActionEventListener
     */
    private $listener;

    /**
     * @param callable|ActionEventListener $listener
     * @throws \InvalidArgumentException
     */
    public function __construct($listener)
    {
        if (! $listener instanceof ActionEventListener && !is_callable($listener)) {
            throw new \InvalidArgumentException('Given parameter listener should be callable or an instance of ActionEventListener. Got ' . (is_object($listener)? get_class($listener) : gettype($listener)));
        }

        $this->listener = $listener;
    }

    /**
     * @return callable|ActionEventListener
     */
    public function getActionEventListener()
    {
        return $this->listener;
    }
}
