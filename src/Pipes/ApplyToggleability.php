<?php

namespace Conquest\Table\Pipes;

use Closure;
use Conquest\Table\Table;

/**
 * @internal
 */
class ApplyToggleability
{
    public function handle(Table $table, Closure $next)
    {
        return $next($table);
    }
}
