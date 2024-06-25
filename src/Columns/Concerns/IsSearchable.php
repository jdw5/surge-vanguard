<?php

namespace Jdw5\Vanguard\Columns\Concerns;

use Closure;
use Override;
use Jdw5\Vanguard\Concerns\HasProperty;

trait IsSearchable
{
    /** Shares property with sort */
    use HasProperty;

    protected bool $searchable = false;

    #[Override]
    private function property(string|Closure $property): static
    {
        $this->setProperty($property);
        return $this;
    }

    public function searchable(string|Closure $property = null): static
    {
        $this->setSearchability(true, $property);
        return $this;
    }
    
    public function search(string|Closure $property = null): static
    {
        return $this->search($property);
    }

    public function notSearchable(): static
    {
        $this->setSearchability(false);
        return $this;
    }

    public function dontSearch(): static
    {
        return $this->notSearchable();
    }

    protected function setSearchability(bool $searchable, string|Closure $property = null): void
    {
        $this->searchable = $searchable;
        $this->setProperty($property);
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function searches(): bool
    {
        return $this->isSearchable();
    }

    public function searchesUsing(): string
    {
        return $this->getProperty();
    }
}