<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Jdw5\Vanguard\Table\Columns\Column;
use Illuminate\Support\Collection;

/**
 * Trait HasColumns
 * 
 * Adds methods to retrieve and define columns from the table.
 * 
 * @property mixed $cachedColumns
 */
trait HasColumns
{
    /** Columns are cached to prevent recalculations */
    private mixed $cachedColumns = null;

    /**
     * Define the columns for the table.
     * 
     * @return array
     */
    protected function defineColumns(): array
    {
        return [];
    }

    /**
     * Retrieve the valid columns for the table
     * 
     * @return Collection
     */
    protected function getTableColumns(): Collection
    {
        $hasDynamics = $this->hasDynamicCols();

        return $this->cachedColumns ??= collect($this->defineColumns())
            ->filter(static fn (Column $column): bool => !$column->isExcluded() && (!$hasDynamics || $column->shouldBeDynamicallyShown())
        );
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
