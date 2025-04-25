<?php

/**
 * @see app/config/app.php
 */

declare(strict_types=1);

namespace App\Application\Config;

use Spiral\Core\InjectableConfig;
use Spiral\Prototype\Annotation\Prototyped;

#[Prototyped('appConfig')]
class AppConfig extends InjectableConfig
{
    public const string CONFIG = 'app';

    protected array $config = [];

    public function getAppUrl(): string
    {
        return $this->config['appUrl'];
    }
}