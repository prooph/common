<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 8:58 PM
 */
namespace ProophTest\Common;

use Prooph\Common\ServiceLocator\ZF2\Zf2ServiceManagerProxy;
use ProophTest\Common\Mock\ServiceInitializerMock;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Class Zf2ServiceManagerProxyTest
 *
 * @package ProophTest\Common
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Zf2ServiceManagerProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_can_be_created_from_zf2_services_confirm_config_array()
    {
        $service = new \stdClass();

        $proxy = Zf2ServiceManagerProxy::createFromConfigurationArray([
            'factories' => [
                'std_service' => function ($services) use ($service) {
                    return $service;
                }
            ],
        ]);

        $this->assertTrue($proxy->has('std_service'));
        $this->assertSame($service, $proxy->get('std_service'));
    }

    /**
     * @test
     */
    function it_can_be_created_with_an_instance_of_a_service_manager()
    {
        $service = new \stdClass();

        $smConfig = new Config([
            'factories' => [
                'std_service' => function ($services) use ($service) {
                        return $service;
                    }
            ],
        ]);

        $sm = new ServiceManager($smConfig);

        $proxy = Zf2ServiceManagerProxy::proxy($sm);

        $this->assertTrue($proxy->has('std_service'));
        $this->assertSame($service, $proxy->get('std_service'));
    }

    /**
     * @test
     */
    function it_allows_overriding_a_service_if_specified()
    {
        $service = new \stdClass();

        $smConfig = new Config([
            'factories' => [
                'std_service' => function ($services) use ($service) {
                        return $service;
                    }
            ],
        ]);

        $sm = new ServiceManager($smConfig);

        $proxy = Zf2ServiceManagerProxy::proxy($sm);

        $anotherService = new \stdClass();

        $proxy->set('std_service', $anotherService, true);

        $this->assertNotSame($service, $proxy->get('std_service'));
        $this->assertSame($anotherService, $proxy->get('std_service'));
    }

    /**
     * @test
     */
    function it_sets_an_alias_for_a_service()
    {
        $service = new \stdClass();

        $smConfig = new Config([
            'factories' => [
                'std_service' => function ($services) use ($service) {
                        return $service;
                    }
            ],
        ]);

        $sm = new ServiceManager($smConfig);

        $proxy = Zf2ServiceManagerProxy::proxy($sm);

        $proxy->setAlias('aliased_service', 'std_service');

        $this->assertTrue($proxy->has('std_service'));
        $this->assertTrue($proxy->has('aliased_service'));
        $this->assertSame($service, $proxy->get('aliased_service'));
    }

    /**
     * @test
     */
    function it_adds_an_initializer_by_using_a_proxy()
    {
        $service = new \stdClass();

        $smConfig = new Config([
            'factories' => [
                'std_service' => function ($services) use ($service) {
                        return $service;
                    }
            ],
        ]);

        $sm = new ServiceManager($smConfig);

        $proxy = Zf2ServiceManagerProxy::proxy($sm);

        $proxy->addInitializer(new ServiceInitializerMock());

        $this->assertSame($service, $proxy->get('std_service'));
        $this->assertSame($proxy, $service->locator);
    }
} 