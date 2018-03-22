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

namespace ProophTest\Common\Event;

use PHPUnit\Framework\TestCase;
use Prooph\Common\Event\DefaultActionEvent;

class DefaultActionEventTest extends TestCase
{
    /**
     * @return DefaultActionEvent
     */
    private function getTestEvent()
    {
        return new DefaultActionEvent('test-event', 'target', ['param1' => 'foo']);
    }

    /**
     * @test
     */
    public function it_can_be_initialized_with_a_name_a_target_and_params(): void
    {
        $event = $this->getTestEvent();

        $this->assertEquals('test-event', $event->getName());
        $this->assertEquals('target', $event->getTarget());
        $this->assertEquals(['param1' => 'foo'], $event->getParams());
    }

    /**
     * @test
     */
    public function it_can_initialized_without_a_target_and_params(): void
    {
        $event = new DefaultActionEvent('test-event');

        $this->assertNull($event->getTarget());
        $this->assertEquals([], $event->getParams());
    }

    /**
     * @test
     */
    public function it_returns_param_if_set(): void
    {
        $event = $this->getTestEvent();
        $this->assertEquals('foo', $event->getParam('param1'));
        $event->setParam('param1', 'bar');
        $this->assertEquals('bar', $event->getParam('param1'));
    }

    /**
     * @test
     */
    public function it_returns_null_if_param_is_not_set_and_no_other_default_is_given(): void
    {
        $this->assertNull($this->getTestEvent()->getParam('unknown'));
    }

    /**
     * @test
     */
    public function it_returns_default_if_param_is_not_set(): void
    {
        $this->assertEquals('default', $this->getTestEvent()->getParam('unknown', 'default'));
    }

    /**
     * @test
     */
    public function it_changes_name_when_new_one_is_set(): void
    {
        $event = $this->getTestEvent();

        $event->setName('new name');

        $this->assertEquals('new name', $event->getName());
    }

    /**
     * @test
     */
    public function it_overrides_params_array_if_new_one_is_set(): void
    {
        $event = $this->getTestEvent();

        $event->setParams(['param_new' => 'bar']);

        $this->assertEquals(['param_new' => 'bar'], $event->getParams());
    }

    /**
     * @test
     */
    public function it_allows_object_implementing_array_access_as_params(): void
    {
        $arrayLikeObject = new \ArrayObject(['object_param' => 'baz']);

        $event = $this->getTestEvent();

        $event->setParams($arrayLikeObject);

        $this->assertSame($arrayLikeObject, $event->getParams());
    }

    /**
     * @test
     */
    public function it_does_not_allow_params_object_that_is_not_of_type_array_access(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $stdObj = new \stdClass();

        $stdObj->param1 = 'foo';

        $this->getTestEvent()->setParams($stdObj);
    }

    /**
     * @test
     */
    public function it_changes_target_if_new_is_set(): void
    {
        $event = $this->getTestEvent();

        $target = new \stdClass();

        $event->setTarget($target);

        $this->assertSame($target, $event->getTarget());
    }

    /**
     * @test
     */
    public function it_indicates_that_propagation_should_be_stopped(): void
    {
        $event = $this->getTestEvent();

        $this->assertFalse($event->propagationIsStopped());

        $event->stopPropagation();

        $this->assertTrue($event->propagationIsStopped());
    }
}
