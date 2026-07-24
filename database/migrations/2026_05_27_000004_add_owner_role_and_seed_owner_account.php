<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(20) NOT NULL DEFAULT 'customer'");
        } elseif (DB::getDriverName() === 'sqlite') {
            $this->rebuildSqliteUsersTable();
        }

        DB::table('users')->updateOrInsert(
            ['email' => 'owner@playhub.com'],
            [
                'name' => 'Owner PlayHub',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
                'phone' => '081234567889',
                'address' => 'Jl. Owner No. 1, Jakarta',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'owner@playhub.com')
            ->where('role', 'owner')
            ->update(['role' => 'admin', 'updated_at' => now()]);
    }

    private function rebuildSqliteUsersTable(): void
    {
        DB::statement('PRAGMA foreign_keys=OFF');
        DB::statement(<<<'SQL'
            CREATE TABLE IF NOT EXISTS users_owner_rebuild (
                id integer primary key autoincrement not null,
                name varchar not null,
                email varchar not null,
                email_verified_at datetime,
                role varchar not null default 'customer',
                phone varchar,
                address text,
                password varchar not null,
                remember_token varchar,
                created_at datetime,
                updated_at datetime
            )
        SQL);
        DB::statement(<<<'SQL'
            INSERT INTO users_owner_rebuild (
                id, name, email, email_verified_at, role, phone, address, password, remember_token, created_at, updated_at
            )
            SELECT
                id, name, email, email_verified_at,
                CASE WHEN role IN ('owner', 'admin', 'customer') THEN role ELSE 'customer' END,
                phone, address, password, remember_token, created_at, updated_at
            FROM users
        SQL);
        DB::statement('DROP TABLE users');
        DB::statement('ALTER TABLE users_owner_rebuild RENAME TO users');
        DB::statement('CREATE UNIQUE INDEX users_email_unique ON users (email)');
        DB::statement('PRAGMA foreign_keys=ON');
    }
};
