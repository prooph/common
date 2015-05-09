<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/9/15 - 9:59 PM
 */
namespace Prooph\Common\ServiceLocator\ZF2;

use Prooph\Common\ServiceLocator\ServiceInitializer;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Zf2InitializerProxy
 *
 * This proxy uses the double dispatch pattern to act as ZF2 Initializer against the ZF2 ServiceManager but proxies
 * the call to the ServiceInitializer and passing it the Zf2ServiceManagerProxy as ServiceLocator
 *
 * @package Prooph\Common\ServiceLocator\ZF2
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Zf2InitializerProxy implements InitializerInterface
{
    /**
     * @var ServiceInitializer
     */
    private $initializer;

    /**
     * @var Zf2ServiceManagerProxy
     */
    private $serviceManagerProxy;

    /**
     * @param ServiceInitializer $initializer
     * @param Zf2ServiceManagerProxy $proxy
     * @return Zf2InitializerProxy
     */
    public static function proxy(ServiceInitializer $initializer, Zf2ServiceManagerProxy $proxy)
    {
        return new self($initializer, $proxy);
    }

    /**
     * @param ServiceInitializer $initializer
     * @param Zf2ServiceManagerProxy $proxy
     */
    private function __construct(ServiceInitializer $initializer, Zf2ServiceManagerProxy $proxy)
    {
        $this->initializer = $initializer;
        $this->serviceManagerProxy = $proxy;
    }

    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        return $this->initializer->initialize($instance, $this->serviceManagerProxy);
    }
}