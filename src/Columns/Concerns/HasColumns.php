<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Columns\BaseColumn;
use Illuminate\Support\Collection;

trait HasColumns
{
    protected Collection $cachedColumns;

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

        if (method_exists($this, 'columns')) {
            return $this->columns();
        }

        return [];
    }

    public function getTableColumns(): Collection
    {
        return $this->cahcedColumns ??= collect($this->getColumns())
            ->filter(fn (BaseColumn $column): bool => $column->authorized());
    }

    /**
     * Retrieve the sortable columns for the table
     * 
     * @return Collection
     */
    public function getSortableColumns(): Collection
    {
        return $this->getTableColumns()->filter(fn (BaseColumn $column): bool => $column->hasSort())->values();
    }

    public function getSearchableColumns(): Collection
    {
        return $this->getTableColumns()->filter(fn (BaseColumn $column): bool => $column->isSearchable())->pluck('name');
    }

    /**
     * Retrieve the key column for the table if one exists
     * 
     * @return Column|null
     */
    public function getKeyColumn(): ?BaseColumn
    {
        return $this->getTableColumns()->first(fn (BaseColumn $column): bool => $column->isKey());
    }

    /**
     * 
     */
    public function getHeadingColumns(): Collection
    {
        return $this->getTableColumns()->filter(fn (BaseColumn $column): bool => $column->isActive())->values();
    }
}
