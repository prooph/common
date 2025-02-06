<?php

/**
 * This file is part of prooph/common.
 * (c) 2014-2025 Alexander Miertsch <kontakt@codeliner.ws>
 * (c) 2015-2025 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Common\Event;

final class DefaultListenerHandler implements ListenerHandler
{
    /**
     * @var callable
     */
    private $listener;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(callable $listener)
    {
        $this->listener = $listener;
    }

    public function getActionEventListener(): callable
    {
        return $this->listener;
    }
}
