<?php

namespace App\Infrastructure\CycleORM\Behavior\Smolid;

use Cycle\ORM\Entity\Behavior\Attribute\Listen;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnCreate;
use Shanginn\Smolid\Smolid;

final readonly class Listener
{
    public function __construct(
        private string $field = 'id',
        private bool $nullable = false,
    ) {}

    #[Listen(OnCreate::class)]
    public function __invoke(OnCreate $event): void
    {
        if (!$this->nullable && !isset($event->state->getData()[$this->field])) {
            $event->state->register($this->field, Smolid::id());
        }
    }
}