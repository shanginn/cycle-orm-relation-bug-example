<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Settings;
use App\Entity\User;
use App\Subscription\SubscriptionLevel;
use Cycle\ORM\EntityManagerInterface;
use DateTimeImmutable;
use Phenogram\Bindings\Types\Interfaces\UserInterface;
use Spiral\Prototype\Annotation\Prototyped;

#[Prototyped('userService')]
final readonly class UserService
{
    public function __construct(
        private UserRepository $repository,
        private EntityManagerInterface $em,
        private Settings\SettingsService $settingsService,
    ) {}

    public function find(int $id): ?User
    {
        return $this->repository->findByPK($id);
    }

    public function findByTelegramUserId(int|string $telegramUserId): ?User
    {
        return $this->repository->findByTelegramUserId($telegramUserId);
    }

    public function getByTelegramUserId(int $telegramUserId): User
    {
        $user = $this->findByTelegramUserId($telegramUserId);
        if ($user === null) {
            $user = new User(telegramUserId: $telegramUserId);
            $this->repository->save($user);
        }

        return $user;
    }

    public function createTelegramUserIfNotExists(
        UserInterface $user,
        ?string $referredBy = null,
    ): User {
        return $this->findByTelegramUserId($user->id)
            ?? $this->createOrUpdateTelegramUser($user, $referredBy);
    }

    public function createOrUpdateTelegramUser(
        UserInterface $user,
        ?string $referredBy = null,
    ): User {
        return $this->createOrUpdateByTelegramUserId(
            $user->id,
            $user->username,
            "{$user->firstName} {$user->lastName}",
            $referredBy,
            $user->languageCode,
        );
    }

    public function createOrUpdateByTelegramUserId(
        int|string $telegramUserId,
        string $username,
        ?string $name = null,
        ?string $referredBy = null,
        ?string $languageCode = null,
    ): User {
        $user = $this->repository->findByTelegramUserId($telegramUserId)
            ?? new User(telegramUserId: $telegramUserId);

        $user->username     = $username;
        $user->name         = $name;
        $user->referredBy   = $referredBy;
        $user->languageCode = $languageCode;

        $this->repository->save($user);

        $this->settingsService->createSettingsIfNotExists($telegramUserId, $languageCode);

        return $user;
    }

    public function save(User $message, bool $run = true): void
    {
        $this->em->persist($message);

        if ($run) {
            $this->em->run();
        }
    }

    public function getSubscriptionLevel(int $telegramUserId): ?SubscriptionLevel
    {
        return $this->findByTelegramUserId($telegramUserId)?->subscriptionLevel;
    }

    public function activateSubscription(int $telegramUserId, SubscriptionLevel $subscriptionLevel): void
    {
        $userInfo                    = $this->getByTelegramUserId($telegramUserId);
        $userInfo->subscriptionLevel = $subscriptionLevel;

        $this->save($userInfo);
    }

    public function deactivateSubscription(int $telegramUserId): void
    {
        $userInfo                    = $this->getByTelegramUserId($telegramUserId);
        $userInfo->subscriptionLevel = null;

        $this->save($userInfo);
    }

    public function updateIsSubscribed(
        int $telegramUserId,
        bool $isSubscribed,
        Settings\Language $channelForLanguage,
    ): void {
        $user = $this->getByTelegramUserId($telegramUserId);

        match ($channelForLanguage) {
            Settings\Language::Russian => $this->updateIsSubscribedRu($user, $isSubscribed),
            Settings\Language::English => $this->updateIsSubscribedEn($user, $isSubscribed),
        };

        $this->save($user);
    }

    public function updateIsSubscribedRu(User $user, bool $isSubscribed): void
    {
        $user->isSubscribed = $isSubscribed;

        if ($isSubscribed) {
            $user->subscribedAt   = new DateTimeImmutable();
            $user->unsubscribedAt = null;
        } else {
            $user->unsubscribedAt = new DateTimeImmutable();
        }
    }

    public function updateIsSubscribedEn(User $user, bool $isSubscribed): void
    {
        $user->isSubscribedEn = $isSubscribed;

        if ($isSubscribed) {
            $user->subscribedAtEn   = new DateTimeImmutable();
            $user->unsubscribedAtEn = null;
        } else {
            $user->unsubscribedAtEn = new DateTimeImmutable();
        }
    }
}