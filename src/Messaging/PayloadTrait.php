<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 7/25/15 - 11:19 PM
 */

namespace Prooph\Common\Messaging;

/**
 * Trait PayloadTrait
 *
 * Use this trait together with the PayloadConstructable interface
 * to use simple message instantiation and default implementations
 * for DomainMessage::payload() and DomainMessage::setPayload()
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
trait PayloadTrait
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->init();
        $this->setPayload($payload);
    }

    /**
     * @return array
     */
    public function payload()
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Use this method to initialize message with defaults or extend your class from
     * \Prooph\Common\Messaging\DomainMessage
     */
    abstract protected function init();
}
