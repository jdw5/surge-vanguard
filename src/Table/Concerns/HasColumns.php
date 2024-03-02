<?php

namespace Jdw5\SurgeVanguard\Table\Concerns;

use Jdw5\SurgeVanguard\Table\Columns\Column;
use Illuminate\Support\Collection;

trait HasColumns
{
    private mixed $cachedColumns = null;

    protected function getTableColumns(): Collection
    {
        return $this->cachedColumns ??= collect($this->defineColumns())
            ->filter(static fn (Column $column): bool => !$column->isHidden());
    }

    protected function getSortableColumns(): Collection
    {
        return $this->getTableColumns()->filter(static fn (Column $column): bool => $column->hasSort());
    }

    protected function defineColumns(): array
    {
        return [];
    }

    protected function findKeyColumn(): ?Column
    {
        return $this->getTableColumns()->first(fn (Column $column): bool => $column->isKey());
    }
}
