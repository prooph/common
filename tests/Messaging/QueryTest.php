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

namespace ProophTest\Common\Messaging;

use PHPUnit\Framework\TestCase;
use Prooph\Common\Messaging\DomainMessage;
use ProophTest\Common\Mock\AskSomething;
use Ramsey\Uuid\Uuid;

class QueryTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_the_message_type_query(): void
    {
        $query = AskSomething::fromArray([
            'message_name' => 'TestQuery',
            'uuid' => Uuid::uuid4()->toString(),
            'created_at' => (new \DateTimeImmutable('now', new \DateTimeZone('UTC'))),
            'payload' => ['query' => 'payload'],
            'metadata' => ['query' => 'metadata'],
        ]);

        $this->assertEquals(DomainMessage::TYPE_QUERY, $query->messageType());
    }
}
