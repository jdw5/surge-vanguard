<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Sorts\ToggleSort;

/**
 * Define whether a column should have toggle sorting enabled.
 */
trait HasSort
{
    protected ?ToggleSort $sort = null;

    /**
     * Define the sorting name, and property to sort by in SQL
     * 
     * @param string|null $name
     * @param string|null $property
     * @return static
     */
    public function sort(string $property = null, string $name = null): static
    {
        $this->setSort($property, $name);
        return $this;
    }

    protected function setSort(string $property = null, string $name = null)
    {
        $this->sort = ToggleSort::make($property ?? $this->getName(), $name ?? $this->getName());
    }

    /**
     * Alias for sort
     * 
     * @param string|null $name
     * @param string|null $property
     * @return static
     */
    public function sortable(string $name = null, string $property = null): static
    {
        return $this->sort($name, $property);
    }

    /**
     * Check if the column has sorting enabled
     * 
     * @return bool
     */
    public function hasSort(): bool
    {
        return !is_null($this->getSort());
    }

    /**
     * Get the sorting class
     */
    public function getSort(): ?ToggleSort
    {
        return $this->sort;
    }

    /**
     * Check if the column is applied
     * 
     * @return bool
     */
    public function isSorting(): bool
    {
        return !!$this->getSort()?->isActive();
    }
}