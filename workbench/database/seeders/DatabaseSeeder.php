<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Workbench\App\Models\TestUser;
use Workbench\Database\Factories\TestUserFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TestUserFactory::new()->count(100)->create();
    }
}
