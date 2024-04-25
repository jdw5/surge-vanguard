<?php

namespace Jdw5\Vanguard;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Refining\Refinement;
use Jdw5\Vanguard\Console\Commands\TableMakeCommand;

class VanguardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /**
         * Vanguard functions through macros to generate the builders by looping
         * over provided refinements.
         * 
         * This is applied to both Eloquent and Query builders.
         */
        Builder::macro('withRefinements', function (array|Collection|null $refinements = []) 
        {
            if ($refinements instanceof Collection) {
                $refinements = $refinements->toArray();
            }

            if (empty($refinements)) {
                try {
                    $refinements = $this->getModel()->getRefinements();
                } catch (\Exception $e) {
                    return $this;
                }
            }

            foreach ($refinements as $refinement) {
                $refinement->refine($this);
            }

            return $this;
        });

        Builder::macro('refine', function (Refinement $refinement) {
            $refinement->refine($this);
            return $this;
        });

        QueryBuilder::macro('withRefinements', function (array|Collection|null $refinements = []) 
        {
            if ($refinements instanceof Collection) {
                $refinements = $refinements->toArray();
            }

            if (empty($refinements)) {
                try {
                    $refinements = $this->getModel()->getRefinements();
                } catch (\Exception $e) {
                    return $this;
                }
            }

            foreach ($refinements as $refinement) {
                $refinement->refine($this);
            }

            return $this;
        });

        QueryBuilder::macro('refine', function (Refinement $refinement) {
            $refinement->refine($this);
            return $this;
        });
    }

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
        ], 'vanguard-stubs');
    }

    public function provides()
    {
        return [
            TableMakeCommand::class
        ];
    }
}
