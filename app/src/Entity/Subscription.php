<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Subscription\SubscriptionRepository;
use App\Entity\Trait\HasTimestamps;
use App\Infrastructure\CycleORM\Behavior\Smolid\Smolid;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table\Index;

#[Entity(repository: SubscriptionRepository::class)]
#[Smolid]
#[Index(name: 'idx_unique_active_subscriptions', columns: ['user_id', 'is_active'], unique: true)]
class Subscription
{
    use HasTimestamps;

    #[Column(type: 'string', primary: true)]
    public string $id;

    public function __construct(
        #[BelongsTo(target: User::class)]
        public User $user,

        #[Column(type: 'boolean', default: false)]
        public bool $isActive = false,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }
}
