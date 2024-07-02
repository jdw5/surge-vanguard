<?php

namespace Conquest\Table\Sorts\Concerns;

use Conquest\Table\Columns\Concerns\HasColumns;
use Conquest\Table\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait HasSorts
{
    use HasSortKey {
        getSortKey as protected;
    }

    protected array $sorts;

    protected function setSorts(array|null $sorts): void
    {
        if (is_null($sorts)) return;
        $this->sorts = $sorts;
    }
    
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

    public function getDefaultSort(): ?Sort
    {
        // Find the first sort which is an instance of Sort and has default set to true
        foreach ($this->getSorts() as $sort) {
            if ($sort instanceof Sort && $sort->isDefault()) {
                return $sort;
            }
        }
    }

    public function sorting(): bool
    {
        return request()->has($this->getSortKey()) && request()->query($this->getSortKey());
    }

    protected function applySorts(Builder|QueryBuilder $query, array $colSorts = []): void
    {
        // Check that there is a sortKey in the query string
        if ($this->sorting()) {
            $mergedSorts = array_merge($this->getSorts(), $colSorts);
            foreach ($this->getSorts() as $sort) {
                $sort->apply($query);
                // Only apply one sort
                if ($sort->isActive()) break;
            }
        } else {
            $this->getDefaultSort()?->apply($query, true);
        }
    }
}