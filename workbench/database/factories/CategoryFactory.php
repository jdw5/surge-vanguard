<?php

namespace Workbench\Database\Factories;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Workbench\App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = fake()->word(),
            'slug' => Str::slug($name),
            'created_at' => now()->subMonth()
        ];
    }
}
