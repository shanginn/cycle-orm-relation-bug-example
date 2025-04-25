<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Config\AppConfig;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Bootloader\DomainBootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container;
use Spiral\Core\CoreInterface;
use Spiral\Cycle\Interceptor\CycleInterceptor;
use Spiral\Domain\GuardInterceptor;
use Spiral\Filters\Model\Mapper\CasterRegistryInterface;

/**
 * @see https://spiral.dev/docs/http-interceptors
 */
final class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [CoreInterface::class => [self::class, 'domainCore']];

    protected const INTERCEPTORS = [
        CycleInterceptor::class,
        GuardInterceptor::class,
    ];

    public function boot(
        CasterRegistryInterface $casterRegistry,
        Container $container,
        DirectoriesInterface $dirs
    ): void {
        $dirs->set('assets', $dirs->get('root') . '/app/assets');
    }

    public function init(ConfiguratorInterface $configurator, EnvironmentInterface $env): void
    {
        $configurator->setDefaults(AppConfig::CONFIG, [
            'appUrl' => $env->get('APP_URL'),
        ]);
    }
}
