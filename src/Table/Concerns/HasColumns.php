<?php

namespace Jdw5\SurgeTable\Table\Concerns;

use Jdw5\SurgeTable\Columns\BaseColumn;
use Illuminate\Support\Collection;

trait HasColumns
{
    private mixed $cachedColumns = null;

    protected function getTableColumns(): Collection
    {
        return $this->cachedColumns ??= collect($this->defineColumns())
            ->filter(static fn (BaseColumn $column): bool => !$column->isHidden());
    }

    protected function defineColumns(): array
    {
        return [];
    }
}
