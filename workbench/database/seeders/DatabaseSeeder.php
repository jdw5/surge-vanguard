<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Workbench\App\Enums\TestRole;
use Workbench\App\Models\TestUser;
use Workbench\Database\Factories\TestUserFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * 1 super admin
         * 4 admins
         * 7 users
         */
        TestUserFactory::new()->create([
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'auser@example.com',
            'role' => TestRole::SUPER_ADMIN->value,
            'created_at' => now(),
        ]);

        TestUserFactory::new()->create([
            'id' => 2,
            'name' => 'B. User',
            'email' => 'buser@example.com',
            'role' => TestRole::ADMIN->value,
            'created_at' => now()->subDay(),
        ]);

        TestUserFactory::new()->create([
            'id' => 3,
            'name' => 'C. User',
            'email' => 'cuser@example.com',
            'role' => TestRole::ADMIN->value,
            'created_at' => now()->subDay(),
        ]);

        TestUserFactory::new()->create([
            'id' => 4,
            'name' => 'D. User',
            'email' => 'duser@example.com',
            'role' => TestRole::USER->value,
            'created_at' => now()->subWeek(),
        ]);

        TestUserFactory::new()->create([
            'id' => 5,
            'name' => 'E. User',
            'email' => 'euser@example.com',
            'role' => TestRole::USER->value,
            'created_at' => now()->subWeek(),
        ]);

        TestUserFactory::new()->create([
            'id' => 6,
            'name' => 'F. User',
            'email' => 'fuser@example.com',
            'role' => TestRole::ADMIN->value,
            'created_at' => now()->subHour(),
        ]);

        TestUserFactory::new()->create([
            'id' => 7,
            'name' => 'G. User',
            'email' => 'guser@example.com',
            'role' => TestRole::ADMIN->value,
            'created_at' => now()->subHour(),
        ]);

        TestUserFactory::new()->create([
            'id' => 8,
            'name' => 'H. User',
            'email' => 'huser@example.com',
            'role' => TestRole::USER->value,
            'created_at' => now()->subDays(2),
        ]);

        TestUserFactory::new()->create([
            'id' => 9,
            'name' => 'I. User',
            'email' => 'iuser@example.com',
            'role' => TestRole::USER->value,
            'created_at' => now()->subDays(2),
        ]);

        TestUserFactory::new()->create([
            'id' => 10,
            'name' => 'J. User',
            'email' => 'juser@example.com',
            'role' => TestRole::USER->value,
            'created_at' => now()->subWeeks(2),
        ]);

        TestUserFactory::new()->create([
            'id' => 11,
            'name' => 'K. User',
            'email' => 'kuser@example.com',
            'role' => TestRole::USER->value,
            'created_at' => now()->subWeeks(2),
        ]);

        TestUserFactory::new()->create([
            'id' => 12,
            'name' => 'Last User',
            'email' => 'last@example.com',
            'role' => TestRole::USER->value,
            'created_at' => now()->subYear(),
        ]);    
    }
}
