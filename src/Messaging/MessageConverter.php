<?php
/**
 * This file is part of the prooph/common.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Messaging;

/**
 * A message converter is able to convert a Message into an array
 */
interface MessageConverter
{
    /**
     * The result array MUST contain the following data structure:
     *
     * [
     *   'message_name' => string,
     *   'uuid' => string,
     *   'payload' => array, //MUST only contain sub arrays and/or scalar types, objects, etc. are not allowed!
     *   'metadata' => array, //MUST only contain key/value pairs with values being only scalar types!
     *   'created_at' => \DateTimeInterface,
     * ]
     *
     * The correct structure and types are asserted by MessageDataAssertion::assert()
     * so make sure that the returned array of your custom conversion logic passes the assertion.
     */
    public function convertToArray(Message $domainMessage): array;
}
