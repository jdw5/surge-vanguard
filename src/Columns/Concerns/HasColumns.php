<?php

namespace Jdw5\Vanguard\Columns\Concerns;

use Jdw5\Vanguard\Table\Columns\Column;
use Illuminate\Support\Collection;

trait HasColumns
{
    private Collection $cachedColumns;

    protected array $columns;

    protected function setColumns(array|null $columns): void
    {
        if (is_null($columns)) return;
        $this->columns = $columns;
    }

    /**
     * Define the columns for the table.
     * 
     * @return array
     */
    protected function getColumns(): array
    {
        if (isset($this->columns)) {
            return $this->columns;
        }

        if (function_exists('columns')) {
            return $this->columns();
        }

        return [];
    }

    /**
     * Retrieve the valid columns for the table
     * 
     * @return Collection
     */
    protected function getTableColumns(): Collection
    {
        return $this->cachedColumns ??= $this->getUncachedTableColumns();
    }

    /**
     * Retrieve the valid columns for the table based on preferences
     * 
     * @param array $preferences An array of column names to show
     */
    private function getUncachedPreferencedTableColumns(array $preferences): Collection
    {
        return collect($this->defineColumns())
            ->filter(static fn (Column $column): bool => !$column->isExcluded() && $column->shouldBeDynamicallyShown($preferences)
        )->values();
    }

    private function getUncachedTableColumns(): Collection
    {
        return collect($this->defineColumns())
            ->filter(static fn (Column $column): bool => !$column->isExcluded()
        )->values();
    }

    /**
     * Retrieve the sortable columns for the table
     * 
     * @return Collection
     */
    protected function getSortableColumns(): Collection
    {
        return $this->getTableColumns()->filter(static fn (Column $column): bool => $column->hasSort());
    }

    /**
     * Retrieve the key column for the table if one exists
     * 
     * @return Column|null
     */
    protected function findKeyColumn(): ?Column
    {
        return $this->getTableColumns()->first(fn (Column $column): bool => $column->isKey());
    }
}
