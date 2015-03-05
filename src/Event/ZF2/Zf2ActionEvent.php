<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 6:59 PM
 */
namespace Prooph\Common\Event\ZF2;

use Prooph\Common\Event\ActionEvent;
use Zend\EventManager\Event;

/**
 * Class Zf2ActionEvent
 *
 * ActionEvent implementation using ZF2 Event
 *
 * @package Prooph\Common\Event\ZF2
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Zf2ActionEvent extends Event implements ActionEvent
{
} 