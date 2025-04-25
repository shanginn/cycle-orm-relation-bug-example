<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\SocketHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use PsrDiscovery\Entities\CandidateEntity;
use PsrDiscovery\Implementations\Psr3\Logs;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Core\Container;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Monolog\Config\MonologConfig;

/**
 * The bootloader is responsible for configuring the application specific loggers.
 *
 * @see https://spiral.dev/docs/basics-logging
 */
final class LoggingBootloader extends Bootloader
{
    public function init(
        MonologBootloader $monolog,
        EnvironmentInterface $env,
        Container $container,
    ): void {
        //        TODO: отправлять в баггрегатор
        //        $handler = new SocketHandler($env->get('MONOLOG_SOCKET_HOST'), chunkSize: 10);
        //        $handler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES));
        //        $monolog->addHandler(
        //            channel: MonologConfig::DEFAULT_CHANNEL,
        //            handler: $handler
        //        );

        // HTTP level errors
        $monolog->addHandler(
            channel: ErrorHandlerMiddleware::class,
            handler: $monolog->logRotate(
                directory('runtime') . 'logs/http.log',
            ),
        );

        // app level errors
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/error.log',
                level: Logger::ERROR,
                maxFiles: 25,
                bubble: false,
            ),
        );

        // debug and info messages via global LoggerInterface
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/debug.log',
            ),
        );

        Logs::add(CandidateEntity::create(
            package: 'monolog/monolog',
            version: '^2.0 | ^3.0',
            builder: fn () => $container->get(LoggerInterface::class),
        ));
    }
}
