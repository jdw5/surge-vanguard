<?php

namespace Conquest\Table\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait HasFilters
{
    protected array $filters;

    protected function setFilters(?array $filters): void
    {
        if (is_null($filters)) {
            return;
        }
        $this->filters = $filters;
    }

    public function getFilters()
    {
        if (isset($this->filters)) {
            return $this->filters;
        }

        if (method_exists($this, 'filters')) {
            return $this->filters();
        }

        return [];
    }

    protected function filter(Builder|QueryBuilder $builder): void
    {
        foreach ($this->getFilters() as $filter) {
            $filter->apply($builder);
        }
    }
}
