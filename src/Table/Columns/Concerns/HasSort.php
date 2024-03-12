<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

use Jdw5\Vanguard\Refining\Sorts\ToggleSort;

trait HasSort
{
    protected ?ToggleSort $sort = null;
    // Need to have the property on the sort

    public function sort(?string $name = null, ?string $property = null): static
    {
        $this->sort = ToggleSort::make($property ?? $this->getName(), $name ?? $this->getName());
        return $this;
    }

    public function hasSort(): bool
    {
        return ! is_null($this->getSort());
    }

    public function getSortName(): ?string
    {
        return $this->hasSort() ? $this->sort->getName() : null;
    }
    
    public function getSort(): ?ToggleSort
    {
        return $this->sort;
    }

    public function isSorting(): bool
    {
        return $this->hasSort() && $this->sort->sortIsActive();
    }

    public function getDirection(): ?string
    {
        return $this->hasSort() ? $this->sort->getDirection() : null;
    }

    public function getNextDirection(): ?string
    {
        return $this->hasSort() ? $this->sort->getNextDirection() : null;
    }
}