<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Sorts\ToggleSort;

/**
 * Assymetric definition
 */
trait IsSortable
{
    protected ?ToggleSort $sort = null;

    /**
     * Alias for sortable
     */
    public function sort(?string $property = null): static
    {
        return $this->sortable($property);
    }

    public function sortable(?string $property = null): static
    {
        $this->setSortable($property);

        return $this;
    }

    public function setSortable(?string $property = null): void
    {
        $this->sort = ToggleSort::make($property ?? $this->getName(), $this->getName());
    }

    public function getSort(): ?ToggleSort
    {
        return $this->evaluate($this->sort);
    }

    public function isSortable(): bool
    {
        return ! $this->isNotSortable();
    }

    public function isNotSortable(): bool
    {
        return is_null($this->sort);
    }

    public function isSorting(): bool
    {
        return (bool) $this->getSort()?->isActive();
    }
}
