<?php

namespace Jdw5\SurgeVanguard;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\SurgeVanguard\Refining\Refinement;
use Jdw5\SurgeVanguard\Console\Commands\TableMakeCommand;

class RefinementProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            $this->commands([
                TableMakeCommand::class,
            ]);
        }
    }

    public function provides()
    {
        return [TableMakeCommand::class];
    }
}
