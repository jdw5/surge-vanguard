<?php

namespace Conquest\Table\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use function Orchestra\Testbench\workbench_path;
use Workbench\App\Providers\WorkbenchServiceProvider;


class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Workbench\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $migrator = app('migrator');
        $migrator->run(workbench_path('database/migrations'));

        $this->seed(DatabaseSeeder::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            WorkbenchServiceProvider::class,

        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('workbench', [
            'start' => '/',
            'install' => true,
            'guard' => 'web',
            'discovers' => [
                'web' => true,
                'api' => false,
                'commands' => false,
                'components' => false,
                'views' => false,
            ],
            'build' => [
                'create-sqlite-db',
                'migrate:fresh --seed',
            ],
            'assets' => [],
            'sync' => [],
        ]);

        $app['config']->set('inertia', [
            'testing' => [
                'ensure_pages_exist' => false,
                'page_paths' => [],
                'page_extensions' => [],
            ],
        ]);
    }

    protected function defineRoutes($router)
    {
        return require workbench_path('routes/web.php');
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }
}
