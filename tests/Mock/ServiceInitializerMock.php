<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/9/15 - 10:06 PM
 */
namespace ProophTest\Common\Mock;


use Prooph\Common\ServiceLocator\ServiceInitializer;
use Prooph\Common\ServiceLocator\ServiceLocator;

final class ServiceInitializerMock implements ServiceInitializer
{

    /**
     * @param mixed $service
     * @param ServiceLocator $serviceLocator
     */
    public function initialize($service, ServiceLocator $serviceLocator)
    {
        if ($service instanceof \stdClass) {
            $service->locator = $serviceLocator;
        }
    }
}