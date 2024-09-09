<?php

namespace App\Table\Pipes;

use Closure;
use Conquest\Table\Pipes\Contracts\FormatsRecords;
use Conquest\Table\Table;

/**
 * @internal
 */
class FormatRecords implements FormatsRecords
{
    public function handle(Table $table, Closure $next)
    {
        $table->setRecords($table->getRecords()->map(function ($record) use ($table) {
            return $table->getTableColumns()->reduce(function ($filteredRecord, BaseColumn $column) use ($record) {
                $columnName = $column->getName();
                $filteredRecord[$columnName] = $column->apply($record[$columnName] ?? null);

                return $filteredRecord;
            }, []);
        }));

        return $next($table);
    }
}
