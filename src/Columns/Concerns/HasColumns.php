<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Columns\Column;
use Illuminate\Support\Collection;

trait HasColumns
{
    private array $cachedColumns;

    protected array $columns;

    protected function setColumns(?array $columns): void
    {
        if (is_null($columns)) {
            return;
        }
        $this->columns = $columns;
    }

    /**
     * Define the columns for the table.
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

    public function getTableColumns(): array
    {
        return $this->cachedColumns ??= array_values(
            array_filter($this->getColumns(), static fn (Column $column): bool => $column->authorized())
        );
    }

    /**
     * Retrieve the sortable columns for the table
     *
     * @return Collection
     */
    public function getSortableColumns(): array
    {
        return array_values(
            array_filter($this->getTableColumns(), static fn (Column $column): bool => $column->hasSort())
        );
    }

    /**
     * Retrieve the key column for the table if one exists
     */
    public function getKeyColumn(): ?Column
    {
        foreach ($this->getTableColumns() as $column) {
            if ($column->isKey()) {
                return $column;
            }
        }
    }

    public function getHeadingColumns(): array
    {
        // Manipulate the show trait to handle the toggleaable columns
        return array_values(
            // array_filter($this->getTableColumns(), static fn (Column $column): bool => $column->isShown() && $column->isToggledOn())
            array_filter($this->getTableColumns(), static fn (Column $column): bool => $column->isShown())
        );
    }
}
