<?php

declare(strict_types=1);

use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

return [
    /**
     * -------------------------------------------------------------------------
     *  Default Monolog handler
     * -------------------------------------------------------------------------.
     */
    'default' => env('MONOLOG_DEFAULT_CHANNEL', 'default'),

    /**
     * -------------------------------------------------------------------------
     *  Global logging level
     * -------------------------------------------------------------------------.
     *
     * Monolog supports the logging levels described by RFC 5424.
     *
     * @see https://seldaek.github.io/monolog/doc/01-usage.html#log-levels
     */
    'globalLevel' => Logger::toMonologLevel(
        env('MONOLOG_DEFAULT_LEVEL', Logger::DEBUG)
    ),

    /**
     * -------------------------------------------------------------------------
     *  Handlers
     * -------------------------------------------------------------------------.
     *
     * @see https://seldaek.github.io/monolog/doc/02-handlers-formatters-processors.html#handlers
     */
    'handlers' => [
        'default' => [
            [
                'class'   => StreamHandler::class,
                'options' => [
                    'stream' => 'php://stdout',
                    'level'  => Logger::DEBUG,
                ],
            ],
            [
                'class'   => 'log.rotate',
                'options' => [
                    'filename' => directory('runtime') . 'logs/app.log',
                    'level'    => Logger::DEBUG,
                ],
            ],
        ],
        'stderr' => [
            ErrorLogHandler::class,
        ],
        'stdout' => [
            [
                'class'   => SyslogHandler::class,
                'options' => [
                    'ident'    => 'app',
                    'facility' => LOG_USER,
                ],
            ],
        ],
    ],

    /**
     * -------------------------------------------------------------------------
     *  Processors
     * -------------------------------------------------------------------------.
     *
     * Processors allows adding extra data for all records.
     *
     * @see https://seldaek.github.io/monolog/doc/02-handlers-formatters-processors.html#processors
     */
    'processors' => [
        'default' => [
            [
                'class'   => PsrLogMessageProcessor::class,
                'options' => [
                    'dateFormat' => 'Y-m-d\TH:i:s.uP',
                ],
            ],
        ],
    ],
];
