<?php
/**
 * This file is part of the prooph/common.
 * (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Messaging;

use Ramsey\Uuid\Uuid;

/**
 * Interface Message
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <contact@prooph.de>
 */
interface Message extends HasMessageName
{
    public const TYPE_COMMAND = 'command';
    public const TYPE_EVENT   = 'event';
    public const TYPE_QUERY   = 'query';

    /**
     * Should be one of Message::TYPE_COMMAND, Message::TYPE_EVENT or Message::TYPE_QUERY
     */
    public function messageType(): string;

    public function uuid(): Uuid;

    public function version(): int;

    public function createdAt(): \DateTimeInterface;

    public function metadata(): array;

    public function withVersion(int $version): Message;

    public function withMetadata(array $metadata): Message;

    /**
     * Returns new instance of message with $key => $value added to metadata
     *
     * Given value must have a scalar type.
     */
    public function withAddedMetadata(string $key, $value): Message;
}
