<?php

namespace Conquest\Table\Pipes;

use Closure;
use Conquest\Table\Table;
use Conquest\Table\Pipes\Contracts\SetsActions;

/**
 * @internal
 */
class SetActions implements SetsActions
{
    public function handle(Table $table, Closure $next)
    {
        
        return $next($table);
    }
}
