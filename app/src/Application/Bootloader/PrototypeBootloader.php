<?php

namespace App\Application\Bootloader;

use Cycle\ORM\EntityManagerInterface;
use Phenogram\Framework\TelegramBot;
use Psr\Container\ContainerInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader as BasePrototypeBootloader;

class PrototypeBootloader extends Bootloader
{
    private const array SHORTCUTS = [
        'em'        => EntityManagerInterface::class,
        'bot'       => TelegramBot::class,
        'container' => ContainerInterface::class,
    ];
    protected const array DEPENDENCIES = [
        BasePrototypeBootloader::class,
    ];

    public function boot(BasePrototypeBootloader $prototype): void
    {
        foreach (self::SHORTCUTS as $property => $class) {
            $prototype->bindProperty($property, $class);
        }
    }
}