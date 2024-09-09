<?php

namespace App\Table\Pipes;

use Closure;
use Conquest\Table\Pipes\Contracts\Filters;
use Conquest\Table\Table;

/**
 * @internal
 */
class ApplyFilters implements Filters
{
    public function handle(Table $table, Closure $next)
    {
        $builder = $table->getResource();
        foreach ($table->getFilters() as $filter) {
            $filter->apply($builder);
        }
        $table->setResource($builder);

        return $next($table);
    }
}
