<?php

namespace Conquest\Table;

use Conquest\Table\Console\Commands\TableMakeCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/table.php', 'table');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Route::bind('table', fn (int|string $value) => Table::from($value));

        if ($this->app->runningInConsole()) {
            $this->commands([
                TableMakeCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs'),
        ], 'conquest-stubs');

        $this->publishes([
            __DIR__.'/../config/table.php' => $this->app['path.config'].DIRECTORY_SEPARATOR.'table.php',
        ]);
    }

    public function provides()
    {
        return [
            TableMakeCommand::class,
        ];
    }
}
