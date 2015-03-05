<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 6:49 PM
 */

namespace Prooph\Common\Event;

/**
 * Interface ActionEventListener
 *
 * @package Prooph\Common\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ActionEventListener 
{
    /**
     * @param ActionEvent $event
     */
    public function __invoke(ActionEvent $event);
} 