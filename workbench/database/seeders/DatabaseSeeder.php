<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Workbench\Database\Factories\CategoryFactory;
use Workbench\Database\Factories\ProductFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryFactory::new()
            ->count(6)
            ->has(ProductFactory::new()->count(20), 'products')
            ->create();

        ProductFactory::new()
            ->create([
                'name' => 'Product 1',
                'description' => null,
                'price' => 1000,
                'created_at' => '2000-01-01 00:00:00',
            ]);
    }
}
