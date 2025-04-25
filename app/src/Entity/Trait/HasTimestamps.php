<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use Cycle\Annotated\Annotation\Column;
use Cycle\ORM\Entity\Behavior;
use DateTimeImmutable;

#[Behavior\CreatedAt]
#[Behavior\UpdatedAt]
trait HasTimestamps
{
    #[Column(type: 'datetime')]
    public DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    public ?DateTimeImmutable $updatedAt = null;
}