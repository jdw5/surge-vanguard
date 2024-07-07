<?php

namespace Conquest\Table\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Testbench\Concerns\WithWorkbench;

use function Orchestra\Testbench\workbench_path;


class TestCase extends Orchestra
{
    use WithWorkbench;
    
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        $app['config']->set('inertia', [
            'testing' => [
                'ensure_pages_exist' => false,
                'page_paths' => [],
                'page_extensions' => [],
            ],
        ]);

        $app['config']->set('view.paths', [
            __DIR__.'/stubs',
            resource_path('views'),
        ]);
    }
}
