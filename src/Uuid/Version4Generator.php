<?php

namespace Prooph\Common\Uuid;

class Version4Generator implements UuidGenerator
{
    public function generate()
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return \Ramsey\Uuid\Uuid::uuid4()->toString();
        } elseif (class_exists('Rhumsaa\Uuid\Uuid')) {
            return \Rhumsaa\Uuid\Uuid::uuid4()->toString();
        }

        throw new \LogicException('Generating UUID requires package ramsey/uuid');
    }
}
