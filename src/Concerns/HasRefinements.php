<?php

namespace Jdw5\Vanguard\Concerns;

use Jdw5\Vanguard\Refining\Refinement;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Refining\Sorts\BaseSort;
use Jdw5\Vanguard\Refining\Filters\BaseFilter;

/**
 * Define a class as having a list of refinement options.
 */
trait HasRefinements
{
    /** Caching the refinements */
    private mixed $refinements = null;

    /**
     * Define the refinements for the class.
     * 
     * @return array
     */
    protected function defineRefinements(): array
    {
        return [];
    }

    /**
     * Retrieve the refinements for the class.
     * 
     * @return Collection
     */
    protected function getRefinements(): Collection
    {
        return $this->refinements ??= collect($this->defineRefinements())
            ->filter(static fn (Refinement $refinement): bool => !$refinement->isExcluded());
    }

    /**
     * Retrieve an associative array of filters keyed to their name.
     * 
     * @return array
     */
    protected function getFilters(): array
    {
        return $this->getRefinements()->mapWithKeys(function (Refinement $refinement) {
            if ($refinement instanceof BaseFilter) {
                return [$refinement->getName() => $refinement];
            }
            return [];
        })->toArray();
    }

    /**
     * Retrieve an associative array of sorts keyed to their name.
     * 
     * @return array
     */
    protected function getSorts(): array
    {
        return $this->getRefinements()->mapWithKeys(function (Refinement $refinement) {
            if ($refinement instanceof BaseSort) {
                return [$refinement->getName() => $refinement];
            }
            return [];
        })->toArray();
    }
}