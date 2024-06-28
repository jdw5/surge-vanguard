<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;
use Conquest\Table\Concerns\HasProperty;
use Override;

trait IsSortable
{
    /** Shares property with search */
    use HasProperty;

    protected bool $sortable = false;

    #[Override]
    private function property(string|Closure $property): static
    {
        $this->setProperty($property);
        return $this;
    }
    
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