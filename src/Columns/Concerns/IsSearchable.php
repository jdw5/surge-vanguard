<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsSearchable
{
    protected bool $searchable = false;

    public function searchable(string|Closure|null $property = null): static
    {
        $this->setSearchability(true, $property);

        return $this;
    }

    public function search(string|Closure|null $property = null): static
    {
        return $this->search($property);
    }

    protected function setSearchable(bool $searchable, string|Closure|null $property = null): void
    {
        $this->searchable = $searchable;
        $this->setProperty($property);
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }
}
