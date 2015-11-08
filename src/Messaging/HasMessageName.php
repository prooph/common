<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 06.07.14 - 18:34
 */

namespace Prooph\Common\Messaging;

/**
 * Interface HasMessageName
 *
 * A message implementing this interface is aware of its name.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface HasMessageName
{
    /**
     * @return string Name of the message
     */
    public function messageName();
}
