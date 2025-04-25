<?php

namespace App\Application\Bootloader;

use App\Application\Config\SmolidConfig;
use Shanginn\Smolid\Smolid;
use Spiral\Boot\Bootloader\Bootloader;

class SmolidBootloader extends Bootloader
{
    public function __construct(
        private SmolidConfig $config,
    ) {}

    public function boot(): void
    {
        Smolid::init(
            alphabet: $this->config->getAlphabet(),
            size: $this->config->getSize(),
        );
    }
}