<?php

namespace Jdw5\Vanguard\Sorts\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait HasSorts
{
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

        if (function_exists('sorts')) {
            return $this->sorts();
        }

        return [];
    }

    protected function applySorts(Builder|QueryBuilder &$query): void
    {
        foreach ($this->getSorts() as $sort) {
            $sort->apply($query);
            // Only apply one sort
            if ($sort->isActive()) break;
        }
    }
}