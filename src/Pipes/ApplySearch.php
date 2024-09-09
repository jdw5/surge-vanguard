<?php

namespace App\Table\Pipes;

use Closure;
use Conquest\Table\Table;

/**
 * @internal
 */
class ApplySearch
{
    public function handle(Table $table, Closure $next)
    {
        return $next($table);
    }
}
