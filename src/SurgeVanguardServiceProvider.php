<?php

namespace Jdw5\SurgeVanguard;

use Jdw5\SurgeVanguard\Refining\Refinement;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

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
        
    }
}
