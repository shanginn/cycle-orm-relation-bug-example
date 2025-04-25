<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Subscription;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class SubscriptionFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Subscription::class;
    }

    public function definition(): array
    {
        return [
            'user' => UserFactory::new()->makeOne(),
        ];
    }

    public function makeEntity(array $definition): object
    {
        return new Subscription(
            user: $definition['user'],
        );
    }
}
