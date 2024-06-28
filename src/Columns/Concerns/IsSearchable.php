<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsSearchable
{
    protected bool $searchable = false;


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