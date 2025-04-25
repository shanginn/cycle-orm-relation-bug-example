<?php

namespace Tests\Entity\Usage;

use App\Entity\Subscription;
use App\Entity\User;
use Cycle\ORM\EntityManagerInterface;
use Database\Factory\SubscriptionFactory;
use Database\Factory\UserFactory;
use DateTimeImmutable;
use Tests\TestCase;

class UsageTest extends TestCase
{
    public function testSubscriptionsIsEmpty()
    {
        $subscriptionRepository = self::getFromContainer(Subscription\SubscriptionRepository::class);
        $userRepository = self::getFromContainer(User\UserRepository::class);

        /** @var User $user */
        $user = UserFactory::new()->createOne();

        /** @var Subscription $subscription */
        $subscription = SubscriptionFactory::new([
            'user'      => $user,
        ])->createOne();

        $em = self::getFromContainer(EntityManagerInterface::class);
        $em->clean();

        /** @var Subscription $subscription */
        $subscription = $subscriptionRepository->find($subscription->getId());
        self::assertNotNull($subscription);

        /** @var User $user */
        $user = $userRepository->find($user->getId());
        self::assertNotNull($user);

        self::assertNotNull($subscription->user);
        self::assertEquals($subscription->user->getId(), $user->getId());
        self::assertNotEmpty($subscription->user->subscriptions);
    }

    public function testSubscriptionsAreSet()
    {
        $subscriptionRepository = self::getFromContainer(Subscription\SubscriptionRepository::class);
        $userRepository = self::getFromContainer(User\UserRepository::class);

        /** @var User $user */
        $user = UserFactory::new()->createOne();

        /** @var Subscription $subscription */
        $subscription = SubscriptionFactory::new([
            'user'      => $user,
        ])->createOne();

        $user->subscriptions[] = $subscription;
        $userRepository->save($user);

        $em = self::getFromContainer(EntityManagerInterface::class);
        $em->clean();

        /** @var Subscription $subscription */
        $subscription = $subscriptionRepository->find($subscription->getId());
        self::assertNotNull($subscription);

        /** @var User $user */
        $user = $userRepository->find($user->getId());
        self::assertNotNull($user);

        self::assertNotNull($subscription->user);
        self::assertEquals($subscription->user->getId(), $user->getId());
        self::assertNotEmpty($subscription->user->subscriptions);
    }
}