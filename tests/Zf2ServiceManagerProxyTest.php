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
} 