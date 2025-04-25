<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Set;
use Spiral\Core\Container;
use Spiral\DatabaseSeeder\Database\Traits\DatabaseAsserts;
use Spiral\DatabaseSeeder\Database\Traits\Helper;
use Spiral\DatabaseSeeder\Database\Traits\ShowQueries;
use Spiral\DatabaseSeeder\Database\Traits\Transactions;
use Spiral\Testing\TestableKernelInterface;
use Spiral\Testing\TestCase as BaseTestCase;
use Spiral\Translator\TranslatorInterface;
use Tests\App\TestKernel;
use Throwable;

class TestCase extends BaseTestCase
{
    use Transactions;
    use Helper;
    use DatabaseAsserts;
    use ShowQueries;

    private static Generator $faker;

    protected function setUp(): void
    {
        $this->beforeBooting(static function (ConfiguratorInterface $config): void {
            if (!$config->exists('session')) {
                return;
            }

            $config->modify('session', new Set('handler', null));
        });

        parent::setUp();

        $container = $this->getContainer();

        if ($container->has(TranslatorInterface::class)) {
            $container->get(TranslatorInterface::class)->setLocale('ru');
        }
    }

    protected function tearDown(): void
    {
        // Uncomment this line if you want to clean up runtime directory.
        // $this->cleanUpRuntimeDirectory();
    }

    public static function faker(): Generator
    {
        if (!isset(self::$faker)) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @throws Throwable
     *
     * @return T
     */
    public function getFromContainer(string $class): object
    {
        return $this->getContainer()->get($class);
    }

    public function createAppInstance(Container $container = new Container()): TestableKernelInterface
    {
        return TestKernel::create(
            directories: $this->defineDirectories(
                $this->rootDirectory(),
            ),
            container: $container,
        );
    }

    public function rootDirectory(): string
    {
        return __DIR__ . '/..';
    }

    public function defineDirectories(string $root): array
    {
        return [
            'root' => $root,
        ];
    }
}
