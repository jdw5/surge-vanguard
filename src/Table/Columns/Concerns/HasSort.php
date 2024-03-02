<?php

namespace Jdw5\SurgeVanguard\Table\Columns\Concerns;

use Jdw5\SurgeVanguard\Refining\Sorts\ToggleSort;

trait HasSort
{
    protected ?ToggleSort $sort = null;

    public function sort(?string $alias = null): static
    {
        $this->sort = ToggleSort::make($this->getName());
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
        return $this->evaluate($this->sort);
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