<?php

namespace Conquest\Table\Sorts\Concerns;

use Conquest\Table\Columns\Concerns\HasColumns;
use Conquest\Table\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

trait Sorts
{
    use HasSort;
    use HasOrder;

    protected array $sorts;

    public function getSorts()
    {
        if (isset($this->sorts)) {
            return $this->sorts;
        }

        if (method_exists($this, 'sorts')) {
            return $this->sorts();
        }
        
        return [];
    }

    protected function setSorts(array|null $sorts): void
    {
        if (is_null($sorts)) return;
        $this->sorts = $sorts;
    }

    public function getDefaultSort(): ?Sort
    {
        return collect($this->getSorts())->first(fn($sort) => $sort->isDefault());
    }

    public function sorting(): bool
    {
        return !is_null($this->getSortNameFromRequest());
    }

    protected function applySorts(Builder|QueryBuilder $query, Collection $colSorts = null): void
    {
        if ($this->sorting()) {
            // $mergedSorts = array_merge($this->getSorts(), $colSorts);
            foreach ($this->getSorts() as $sort) {
                $sort->apply($query, $this->getSort(), $this->getOrder());
                // Only apply one sort
                if ($sort->isActive()) break;
            }
        } else {
            $this->getDefaultSort()?->handle($query);
        }
    }
}
