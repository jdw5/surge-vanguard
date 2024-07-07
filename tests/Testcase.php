<?php

namespace Conquest\Table\Tests;

use Conquest\Table\TableServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\App\Providers\WorkbenchServiceProvider;
use Workbench\Database\Seeders\DatabaseSeeder;

use function Orchestra\Testbench\artisan; 
use function Orchestra\Testbench\workbench_path;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh', ['--database' => 'testing']);
        $this->artisan('db:seed', ['--database' => 'testing']);        
    }

    protected function getPackageProviders($app)
    {
        return [
            WorkbenchServiceProvider::class,
            TableServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

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
                'migrate:fresh',
            ],
            'assets' => [],
            'sync' => [],
        ]);

        // $app['config']->set('inertia', [
        //     'testing' => [
        //         'ensure_pages_exist' => false,
        //         'page_paths' => [],
        //         'page_extensions' => [],
        //     ],
        // ]);
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