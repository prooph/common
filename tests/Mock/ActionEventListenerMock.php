<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 3/5/15 - 7:48 PM
 */
namespace ProophTest\Common\Mock;

use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ActionEventListener;

/**
 * Class ActionEventListenerMock
 *
 * @package ProophTest\Common\Mock
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ActionEventListenerMock implements ActionEventListener
{
    /**
     * @var ActionEvent
     */
    public $lastEvent;

    /**
     * @param ActionEvent $event
     */
    public function __invoke(ActionEvent $event)
    {
        $this->lastEvent = $event;
    }
}
