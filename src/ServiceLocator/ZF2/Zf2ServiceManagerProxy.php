<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 8:48 PM
 */
namespace Prooph\Common\ServiceLocator\ZF2;

use Prooph\Common\ServiceLocator\ServiceLocator;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Class Zf2ServiceManagerProxy
 *
 * @package Prooph\Common\ServiceLocator\ZF2
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Zf2ServiceManagerProxy implements ServiceLocator
{
    /**
     * @var ServiceManager
     */
    private $zf2ServiceManager;

    /**
     * @param array $config
     * @return Zf2ServiceManagerProxy
     */
    public static function createFromConfigurationArray(array $config)
    {
        $config = new Config($config);

        $self = new self();

        $self->zf2ServiceManager = new ServiceManager($config);

        return $self;
    }

    /**
     * @param string $serviceName
     * @return mixed
     */
    public function get($serviceName)
    {
        return $this->getServiceManager()->get($serviceName);
    }

    /**
     * @param string $serviceName
     * @return bool
     */
    public function has($serviceName)
    {
        return $this->getServiceManager()->has($serviceName);
    }

    /**
     * @param string $serviceName
     * @param mixed $service
     */
    public function set($serviceName, $service)
    {
        $this->getServiceManager()->setService($serviceName, $service);
    }

    /**
     * @return ServiceManager
     */
    private function getServiceManager()
    {
        if (is_null($this->zf2ServiceManager)) {
            $this->zf2ServiceManager = new ServiceManager();
        }

        return $this->zf2ServiceManager;
    }
}