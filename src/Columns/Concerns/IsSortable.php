<?php

namespace Jdw5\Vanguard\Columns\Concerns;

use Closure;
use Jdw5\Vanguard\Concerns\HasProperty;

trait IsSortable
{
    use HasProperty;

    protected bool $sortable = false;
    
    public function sort(string|Closure $property = null): static
    {
        $this->setSortability(true, $property);
        return $this;
    }
    
    public function sortable(string|Closure $property = null): static
    {
        return $this->search($property);
    }

    public function dontSort(): static
    {
        $this->setSortability(false);
        return $this;
    }

    public function notSortable(): static
    {
        return $this->dontSort();
    }

    protected function setSortability(bool $sortable, string|Closure $property = null): void
    {
        $this->sortable = $sortable;
        $this->setProperty($property);
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function sorts(): bool
    {
        return $this->isSortable();
    }

    public function sortsUsing(): string
    {
        return $this->getProperty();
    }
}