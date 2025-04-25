<?php

declare(strict_types=1);

namespace App\Entity\Subscription;

use App\Entity\Subscription;
use App\Exception\SubscriptionNotFoundException;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;
use DateTimeImmutable;
use Spiral\Prototype\Annotation\Prototyped;

/**
 * @template T of Subscription
 *
 * @extends Repository<T>
 */
#[Prototyped('subscriptionRepository')]
final class SubscriptionRepository extends Repository
{
    public function __construct(
        Select $select,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($select);
    }

    public function findOrFail(string $id): Subscription
    {
        $user = $this->find($id);

        if ($user === null) {
            throw new SubscriptionNotFoundException(
                criteria: ['id' => $id],
            );
        }

        return $user;
    }

    public function find(string $id): ?Subscription
    {
        return $this->findByPK($id);
    }

    public function findBy(array $scope = []): ?Subscription
    {
        return parent::findOne($scope);
    }

    public function exists(array $scope = []): bool
    {
        return $this->select()->where($scope)->count() > 0;
    }

    public function isUserSubscribed(string $userId): bool
    {
        return $this->exists([
            'user_id'       => $userId,
            'ended_at'      => null,
            'subscribed_at' => ['>=' => new DateTimeImmutable()],
        ]);
    }

    public function findActiveForUser(string $userId): ?Subscription
    {
        return $this->findBy([
            'user_id'  => $userId,
            'ended_at' => null,
        ]);
    }

    public function delete(Subscription $subscription, bool $run = true): void
    {
        $this->em->delete($subscription);

        $run && $this->em->run();
    }

    public function save(Subscription $subscription, bool $run = true): void
    {
        $this->em->persist($subscription);

        $run && $this->em->run();
    }
}
