<?php

namespace Jdw5\Vanguard\Filters\Concerns;

trait HasFilters
{
    protected array $filters;

    protected function setFilters(array|null $filters): void
    {
        if (is_null($filters)) return;
        $this->filters = $filters;
    }

    public function getFilters()
    {
        if (isset($this->filters)) {
            return $this->filters;
        }

        if (function_exists('filters')) {
            return $this->filters();
        }

        return [];
    }
}