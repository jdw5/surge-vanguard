<?php

namespace Jdw5\SurgeVanguard\Concerns;

use Jdw5\SurgeVanguard\Refining\Refinement;
use Illuminate\Support\Collection;
use Jdw5\SurgeVanguard\Refining\Sorts\BaseSort;
use Jdw5\SurgeVanguard\Refining\Filters\BaseFilter;

trait HasRefinements
{
    private mixed $cachedRefinements = null;
    protected array $addedRefinements = [];

    protected function getRefinements(): Collection
    {
        return $this->cachedRefinements ??= collect($this->defineRefinements())
            ->filter(static fn (Refinement $refinement): bool => !$refinement->isHidden());
    }

    protected function getFilters(): Collection
    {
        return $this->getRefinements()->filter(static fn (Refinement $refinement): bool => $refinement instanceof BaseFilter);
    }

    protected function getSorts(): Collection
    {
        return $this->getRefinements()->filter(static fn (Refinement $refinement): bool => $refinement instanceof BaseSort);
    }

    protected function defineRefinements(): array
    {
        return [];
    }
}