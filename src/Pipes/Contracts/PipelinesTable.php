<?php

declare(strict_types=1);

namespace Conquest\Table\Pipes\Contracts;

use Closure;
use Conquest\Table\Table;

/**
 * @internal
 */
interface PipelinesTable
{
    public function handle(Table $table, Closure $next);
}