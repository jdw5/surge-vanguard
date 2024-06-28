<?php

namespace Workbench\Database\Factories;

use Illuminate\Support\Str;
use Workbench\App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Enums\Status;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => Str::uuid(),
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'price' => fake()->randomNumber(4),
            'best_seller' => fake()->boolean(),
            'status' => fake()->randomElement(Status::cases())
        ];
    }
}
