<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 8:07 PM
 */

namespace Prooph\Common\Event;

/**
 * Interface ListenerHandler
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ListenerHandler 
{
    /**
     * @return ActionEventListener
     */
    public function getActionEventListener();
} 