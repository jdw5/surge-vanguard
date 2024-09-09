<?php

namespace App\Table\Pipes;

use Closure;
use Conquest\Table\Table;

/**
 * @internal
 */
class ApplyFilters
{
    public function handle(Table $table, Closure $next)
    {
        $builder = $table->getResource();
        foreach ($table->getFilters() as $filter) {
            $filter->apply($builder);
        }

        return $next($table);
    }
}
