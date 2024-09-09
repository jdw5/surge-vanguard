<?php

namespace App\Table\Pipes;

use Closure;
use Conquest\Table\Table;

/**
 * @internal
 */
class ApplySorts
{
    public function handle(Table $table, Closure $next)
    {
        return $next($table);
    }
}
