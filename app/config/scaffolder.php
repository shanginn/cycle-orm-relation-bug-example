<?php

declare(strict_types=1);

use Spiral\Scaffolder\Config\ScaffolderConfig;
use Spiral\Scaffolder\Declaration;

/**
 * Scaffolder configuration.
 *
 * @see https://spiral.dev/docs/basics-scaffolding
 * @see ScaffolderConfig
 */
return [
    // Default namespace for all declarations
    'namespace' => 'App',

    'declarations' => [
        Declaration\BootloaderDeclaration::TYPE => [
            'namespace' => 'Application\Bootloader',
        ],
        Declaration\ConfigDeclaration::TYPE => [
            'namespace' => 'Application\Config',
        ],
        Declaration\ControllerDeclaration::TYPE => [
            'namespace' => 'Endpoint\Web',
        ],
        Declaration\FilterDeclaration::TYPE => [
            'namespace' => 'Endpoint\Web\Filter',
        ],
        Declaration\MiddlewareDeclaration::TYPE => [
            'namespace' => 'Endpoint\Web\Middleware',
        ],
        Declaration\CommandDeclaration::TYPE => [
            'namespace' => 'Endpoint\Console',
        ],
        Declaration\JobHandlerDeclaration::TYPE => [
            'namespace' => 'Endpoint\Job',
        ],
    ],
];
