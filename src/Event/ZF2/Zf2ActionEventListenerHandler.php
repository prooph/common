<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 7:36 PM
 */
namespace Prooph\Common\Event\ZF2;

use Prooph\Common\Event\ActionEventListener;
use Prooph\Common\Event\ListenerHandler;
use Zend\Stdlib\CallbackHandler;

/**
 * Class Zf2ActionEventListener
 *
 * @package Prooph\Common\Event\ZF2
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Zf2ActionEventListenerHandler implements ListenerHandler
{
    /**
     * @var CallbackHandler
     */
    private $callbackHandler;

    /**
     * @param CallbackHandler $callbackHandler
     */
    public function __construct(CallbackHandler $callbackHandler)
    {
        $this->callbackHandler = $callbackHandler;
    }

    /**
     * @return CallbackHandler
     */
    public function getCallbackHandler()
    {
        return $this->callbackHandler;
    }

    /**
     * @return ActionEventListener
     */
    public function getActionEventListener()
    {
        return $this->callbackHandler->getCallback();
    }
}