<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Columns\BaseColumn;

trait HasColumns
{
    private array $cachedColumns;

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

        if (method_exists($this, 'columns')) {
            return $this->columns();
        }

        return [];
    }

    public function getTableColumns(): array
    {
        return $this->cachedColumns ??= array_values(
            array_filter($this->getColumns(), static fn (BaseColumn $column): bool => $column->authorized())
        );
    }

    /**
     * Retrieve the sortable columns for the table
     * 
     * @return array
     */
    public function getSortableColumns(): array
    {
        return array_values(
            array_filter($this->getTableColumns(), static fn (BaseColumn $column): bool => $column->hasSort())
        );
    }

    /**
     * Retrieve the key column for the table if one exists
     * 
     * @return Column|null
     */
    public function getKeyColumn(): ?BaseColumn
    {
        foreach ($this->getColumns() as $column) {
            if ($column->isKey()) {
                return $column;
            }
        }
        return null;
    }

    public function getHeadingColumns(): array
    {
        // Manipulate the show trait to handle the toggleaable columns
        return array_values(
            array_filter($this->getTableColumns(), static fn (BaseColumn $column): bool => $column->isKey() || $column->isActive())
        );
    }
}
