<?php

namespace App\Table\Pipes;

use Closure;
use Conquest\Table\Pipes\Contracts\Searches;
use Conquest\Table\Table;

/**
 * @internal
 */
class ApplySearch implements Searches
{
    public function handle(Table $table, Closure $next)
    {
        $builder = $table->getResource();
        $searches = array_merge(
            $table->getSearch(),
            $table->getSearchableColumns()->toArray()
        );
        // Handle scout case

        return $next($table);
    }
}
