<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Jdw5\Vanguard\Table\Columns\Column;
use Illuminate\Support\Collection;

trait HasColumns
{
    private mixed $cachedColumns = null;

    protected function defineColumns(): array
    {
        return [];
    }

    protected function getTableColumns(): Collection
    {
        return $this->cachedColumns ??= collect($this->defineColumns())
            ->filter(static fn (Column $column): bool => !$column->isExcluded());
    }

    protected function getSortableColumns(): Collection
    {
        return $this->getTableColumns()->filter(static fn (Column $column): bool => $column->hasSort());
    }

    protected function findKeyColumn(): ?Column
    {
        return $this->getTableColumns()->first(fn (Column $column): bool => $column->isKey());
    }
}
