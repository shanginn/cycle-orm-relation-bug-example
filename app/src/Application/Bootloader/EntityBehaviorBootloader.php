<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Cycle\ORM\Entity\Behavior\EventDrivenCommandGenerator;
use Cycle\ORM\Transaction\CommandGeneratorInterface;
use Spiral\Boot\Bootloader\Bootloader;

class EntityBehaviorBootloader extends Bootloader
{
    protected const BINDINGS = [
        CommandGeneratorInterface::class => EventDrivenCommandGenerator::class,
    ];
}