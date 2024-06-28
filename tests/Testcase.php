<?php

namespace Conquest\Table\Tests;

use Illuminate\Contracts\Config\Repository;
use Conquest\Table\ConquestTableServiceProvider;

use function Orchestra\Testbench\workbench_path;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Workbench\Database\Seeders\DatabaseSeeder;

class TestCase extends TestbenchTestCase
{
    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            ConquestTableServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
        $this->seed(DatabaseSeeder::class);
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function defineRoutes($router)
    {
        require workbench_path('routes/web.php');
    }
}