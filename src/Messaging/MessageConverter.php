<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 7/26/15 - 3:27 PM
 */

namespace Prooph\Common\Messaging;

/**
 * Interface MessageConverter
 *
 * A message converter is able to convert a Message into an array
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface MessageConverter
{
    /**
     *
     *
     * @param Message $domainMessage
     * @return array
     */
    public function convertToArray(Message $domainMessage);
}
