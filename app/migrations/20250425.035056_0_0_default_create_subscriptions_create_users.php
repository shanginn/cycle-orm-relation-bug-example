<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault3733c522d45c53d7daf40d31e8cd3df6 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
        ->addColumn('created_at', 'datetime', [
            'nullable'     => false,
            'defaultValue' => 'CURRENT_TIMESTAMP',
            'withTimezone' => false,
            'comment'      => '',
        ])
        ->addColumn('updated_at', 'datetime', [
            'nullable'     => true,
            'defaultValue' => null,
            'withTimezone' => false,
            'comment'      => '',
        ])
        ->addColumn('id', 'text', ['nullable' => false, 'defaultValue' => null, 'comment' => ''])
        ->addColumn('telegram_user_id', 'bigInteger', ['nullable' => false, 'defaultValue' => null, 'comment' => ''])
        ->addIndex(['telegram_user_id'], ['name' => 'users_index_telegram_user_id_680b06a0ad82b', 'unique' => true])
        ->setPrimaryKeys(['id'])
        ->create();
        $this->table('subscriptions')
        ->addColumn('created_at', 'datetime', [
            'nullable'     => false,
            'defaultValue' => 'CURRENT_TIMESTAMP',
            'withTimezone' => false,
            'comment'      => '',
        ])
        ->addColumn('updated_at', 'datetime', [
            'nullable'     => true,
            'defaultValue' => null,
            'withTimezone' => false,
            'comment'      => '',
        ])
        ->addColumn('id', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255, 'comment' => ''])
        ->addColumn('is_active', 'boolean', ['nullable' => false, 'defaultValue' => false, 'comment' => ''])
        ->addColumn('user_id', 'text', ['nullable' => false, 'defaultValue' => null, 'comment' => ''])
        ->addIndex(['user_id'], ['name' => 'subscriptions_index_user_id_680b06a0ad5e5', 'unique' => false])
        ->addIndex(['user_id', 'is_active'], ['name' => 'idx_unique_active_subscriptions', 'unique' => true])
        ->addForeignKey(['user_id'], 'users', ['id'], [
            'name'        => 'subscriptions_foreign_user_id_680b06a0ad5fc',
            'delete'      => 'CASCADE',
            'update'      => 'CASCADE',
            'indexCreate' => true,
        ])
        ->setPrimaryKeys(['id'])
        ->create();
    }

    public function down(): void
    {
        $this->table('subscriptions')->drop();
        $this->table('users')->drop();
    }
}
