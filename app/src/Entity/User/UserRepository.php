<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\Table;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;
use Spiral\Prototype\Annotation\Prototyped;

/**
 * @template T of User
 *
 * @extends Repository<T>
 */
#[Prototyped('userRepository')]
final class UserRepository extends Repository
{
    private Table $table;

    public function __construct(
        Select $select,
        private readonly EntityManagerInterface $em,
        private readonly DatabaseInterface $db,
    ) {
        parent::__construct($select);

        $table = $this->db->table('users');

        assert($table instanceof Table);

        $this->table = $table;
    }

    public function findOrFail(string $id): User
    {
        $user = $this->find($id);

        if ($user === null) {
            throw new UserNotFoundException(
                criteria: ['id' => $id],
            );
        }

        return $user;
    }

    public function find(string $id): ?User
    {
        return $this->findByPK($id);
    }

    public function findBy(array $scope = []): ?User
    {
        return parent::findOne($scope);
    }

    public function exists(string $id): bool
    {
        return $this->select()->wherePK($id)->count() > 0;
    }

    public function delete(User $user, bool $run = true): void
    {
        $this->em->delete($user);

        $run && $this->em->run();
    }

    public function save(User $user, bool $run = true): void
    {
        $this->em->persist($user);

        $run && $this->em->run();
    }

    public function findByTelegramUserId(int|string $telegramUserId): ?User
    {
        return $this->findOne(['telegramUserId' => (int) $telegramUserId]);
    }

    /**
     * @return array<int>
     */
    public function getAllUsersTelegramIds(): array
    {
        $result = $this->table->select()
            ->columns('telegram_user_id')
            ->orderBy('created_at', 'ASC')
            ->fetchAll();

        return array_column($result, 'telegram_user_id');
    }
}
