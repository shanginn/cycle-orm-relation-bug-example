<?php

declare(strict_types=1);

use Spiral\TemporalBridge\Config\ClientConfig;
use Spiral\TemporalBridge\Config\ConnectionConfig;
use Temporal\Client\ClientOptions;
use Temporal\Worker\WorkerOptions;

return [
    'client'  => env('TEMPORAL_CONNECTION', 'default'),
    'clients' => [
        'default' => new ClientConfig(
            new ConnectionConfig(
                address: env('TEMPORAL_ADDRESS', 'localhost:7233'),
            ),
            new ClientOptions()->withNamespace(env('TEMPORAL_NAMESPACE', 'default')),
        ),
    ],
    'defaultWorker' => 'default',
    'workers'       => [
        'default' => WorkerOptions::new(),
    ],
];
