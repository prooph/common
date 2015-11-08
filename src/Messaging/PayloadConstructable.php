<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 7/25/15 - 11:21 PM
 */

namespace Prooph\Common\Messaging;

/**
 * Interface PayloadConstructable
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface PayloadConstructable
{
    public function __construct(array $payload);
}
