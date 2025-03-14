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

namespace ProophTest\Common\Mock;

use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\Common\Messaging\Query;

final class AskSomething extends Query implements PayloadConstructable
{
    use PayloadTrait;
}
