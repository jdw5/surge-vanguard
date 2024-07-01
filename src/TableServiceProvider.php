<?php

namespace Conquest\Table;

use Conquest\Table\Console\Commands\TableMakeCommand;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TableMakeCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs'),
        ], 'conquest-stubs');
    }

    public function provides()
    {
        return [
            TableMakeCommand::class,
        ];
    }
}
