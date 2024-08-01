<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Columns\BaseColumn;
use Illuminate\Support\Collection;

trait HasColumns
{
    protected Collection $cachedColumns;

    protected array $columns;

    /**
     * @param  array<BaseColumn>|null  $columns
     */
    protected function setColumns(?array $columns): void
    {
        if (is_null($columns)) {
            return;
        }
        $this->columns = $columns;
    }

    /**
     * @return array<BaseColumn>
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
        return $this->cachedColumns ??= collect($this->getColumns())
            ->filter(fn (BaseColumn $column): bool => $column->isAuthorized());
    }

    public function getSortableColumns(): Collection
    {
        return $this->getTableColumns()->filter(fn (BaseColumn $column): bool => $column->hasSort())->values();
    }

    public function getSearchableColumns(): Collection
    {
        return $this->getTableColumns()->filter(fn (BaseColumn $column): bool => $column->isSearchable())->pluck('name');
    }

    public function getKeyColumn(): ?BaseColumn
    {
        return $this->getTableColumns()->first(fn (BaseColumn $column): bool => $column->isKey());
    }

    public function getHeadingColumns(): Collection
    {
        return $this->getTableColumns()->filter(fn (BaseColumn $column): bool => $column->isActive())->values();
    }
}
