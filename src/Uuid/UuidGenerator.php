<?php

namespace Prooph\Common\Uuid;

interface UuidGenerator
{
    /**
     * @return string
     */
    public function generate();
}
