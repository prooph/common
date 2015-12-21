<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 5/22/15 - 10:28 PM
 */
namespace ProophTest\Common\Messaging;

use Prooph\Common\Messaging\DomainMessage;
use ProophTest\Common\Mock\AskSomething;
use Prooph\Common\Uuid;

final class QueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_has_the_message_type_query()
    {
        $query = AskSomething::fromArray([
            'message_name' => 'TestQuery',
            'uuid' => (new Uuid\Version4Generator())->generate(),
            'version' => 1,
            'created_at' => (new \DateTimeImmutable('now', new \DateTimeZone('UTC'))),
            'payload' => ['query' => 'payload'],
            'metadata' => ['query' => 'metadata']
        ]);

        $this->assertEquals(DomainMessage::TYPE_QUERY, $query->messageType());
    }
}
