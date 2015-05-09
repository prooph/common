<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/5/15 - 8:46 PM
 */

namespace Prooph\Common\ServiceLocator;

/**
 * Interface ServiceLocator
 *
 * @package Prooph\Common\ServiceLocator
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ServiceLocator 
{
    /**
     * @param string $serviceName
     * @return mixed
     */
    public function get($serviceName);

    /**
     * @param string $serviceName
     * @return bool
     */
    public function has($serviceName);

    /**
     * @param string $serviceName
     * @param mixed $service
     * @param bool $allowOverride
     */
    public function set($serviceName, $service, $allowOverride = false);

    /**
     * @param string $alias
     * @param string $orgServiceName
     */
    public function setAlias($alias, $orgServiceName);

    /**
     * @param ServiceInitializer $initializer
     */
    public function addInitializer(ServiceInitializer $initializer);
} 