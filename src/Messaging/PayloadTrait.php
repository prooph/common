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
 * Use this trait together with the PayloadConstructable interface
 * to use simple message instantiation and default implementations
 * for DomainMessage::payload() and DomainMessage::setPayload()
 */
trait PayloadTrait
{
    /**
     * @var array
     */
    protected $payload;

    public function __construct(array $payload = [])
    {
        $this->init();
        $this->setPayload($payload);
    }

    public function payload(): array
    {
        return $this->payload;
    }

    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * Use this method to initialize message with defaults or extend your class from DomainMessage
     */
    abstract protected function init(): void;
}
