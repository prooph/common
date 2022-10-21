<?php

$config = new Prooph\CS\Config\Prooph();
$config->getFinder()->in(__DIR__);

$config->setCacheFile(__DIR__ . '/.php_cs.cache');

return $config;
