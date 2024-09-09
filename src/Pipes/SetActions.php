<?php

namespace Conquest\Table\Pipes;

use Closure;
use Conquest\Table\Pipes\Contracts\SetsActions;
use Conquest\Table\Table;

/**
 * @internal
 */
class SetActions implements SetsActions
{
    public function handle(Table $table, Closure $next)
    {
        // Set an action
        return $next($table);
    }
}
