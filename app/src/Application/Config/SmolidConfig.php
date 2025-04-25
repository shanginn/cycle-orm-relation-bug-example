<?php

namespace App\Application\Config;

use Spiral\Core\InjectableConfig;

final class SmolidConfig extends InjectableConfig
{
    public const string CONFIG = 'smolid';

    protected array $config = [];

    public function getAlphabet(): string
    {
        return $this->config['alphabet'];
    }

    public function getSize(): int
    {
        return $this->config['size'];
    }
}