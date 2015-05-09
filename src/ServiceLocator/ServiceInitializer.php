<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/9/15 - 9:57 PM
 */

namespace Prooph\Common\ServiceLocator;

/**
 * Interface ServiceInitializer
 *
 * @package Prooph\Common\ServiceLocator
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ServiceInitializer 
{
    /**
     * @param mixed $service
     * @param ServiceLocator $serviceLocator
     */
    public function initialize($service, ServiceLocator $serviceLocator);
} 