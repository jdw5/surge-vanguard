<?php

namespace Jdw5\Vanguard\Concerns;

use Jdw5\Vanguard\Refining\Refinement;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Refining\Sorts\BaseSort;
use Jdw5\Vanguard\Refining\Filters\BaseFilter;

trait HasRefinements
{
    private mixed $cachedRefinements = null;

    protected function getRefinements(): Collection
    {
        return $this->cachedRefinements ??= collect($this->defineRefinements())
            ->filter(static fn (Refinement $refinement): bool => !$refinement->isExcluded());
    }

    protected function getFilters(): array
    {
        return $this->getRefinements()->mapWithKeys(function (Refinement $refinement) {
            if ($refinement instanceof BaseFilter) {
                return [$refinement->getName() => $refinement];
            }
            return [];
        })->toArray();
    }


    protected function getSorts(): array
    {
        return $this->getRefinements()->mapWithKeys(function (Refinement $refinement) {
            if ($refinement instanceof BaseSort) {
                return [$refinement->getName() => $refinement];
            }
            return [];
        })->toArray();
    }

    protected function defineRefinements(): array
    {
        return [];
    }
}