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

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface Message extends HasMessageName
{
    public const TYPE_COMMAND = 'command';
    public const TYPE_EVENT = 'event';
    public const TYPE_QUERY = 'query';

    /**
     * Should be one of Message::TYPE_COMMAND, Message::TYPE_EVENT or Message::TYPE_QUERY
     */
    public function messageType(): string;

    public function uuid(): UuidInterface;

    public function createdAt(): DateTimeImmutable;

    public function payload(): array;

    public function metadata(): array;

    public function withMetadata(array $metadata): Message;

    /**
     * Returns new instance of message with $key => $value added to metadata
     *
     * Given value must have a scalar or array type.
     */
    public function withAddedMetadata(string $key, $value): Message;
}
