<?php
/**
 * This file is part of the prooph/common.
 *  (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 *  (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare (strict_types=1);

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

    public function __construct(array $payload)
    {
        $this->init();
        $this->setPayload($payload);
    }

    public function payload() : array
    {
        return $this->payload;
    }

    protected function setPayload(array $payload) : void
    {
        $this->payload = $payload;
    }

    /**
     * Use this method to initialize message with defaults or extend your class from
     * \Prooph\Common\Messaging\DomainMessage
     */
    abstract protected function init() : void;
}
