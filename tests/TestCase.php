<?php

namespace Conquest\Table\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Workbench\Database\Seeders\DatabaseSeeder;

use function Orchestra\Testbench\workbench_path;

class TestCase extends Orchestra
{
    use WithWorkbench;
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Conquest\\Form\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
        $this->artisan('migrate', ['--database' => 'testing'])->run();
        $this->seed(DatabaseSeeder::class);
    }
}
