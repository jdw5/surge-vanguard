<?php

namespace Jdw5\Vanguard\Concerns;

trait HasFilters
{
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