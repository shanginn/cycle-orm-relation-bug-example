<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\HasTimestamps;
use App\Entity\User\Gender;
use App\Entity\User\UserRepository;
use App\Infrastructure\CycleORM\Behavior\Smolid\Smolid;
use App\Subscription\SubscriptionLevel;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;

#[Entity(
    repository: UserRepository::class
)]
#[Smolid]
#[Index(columns: ['telegram_user_id'], unique: true)]
class User
{
    use HasTimestamps;

    #[Column(type: 'text', primary: true)]
    public string $id;

    public function __construct(
        #[Column(type: 'bigInteger')]
        public int $telegramUserId,

        #[HasMany(target: Subscription::class, load: 'eager')]
        public array $subscriptions = [],
    ) {}

    public function getActiveSubscription(): ?Subscription
    {
        return array_find($this->subscriptions, fn (Subscription $subscription) => $subscription->isActive);
    }

    public function getId(): string
    {
        return $this->id;
    }
}