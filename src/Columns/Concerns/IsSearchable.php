<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsSearchable
{
    protected bool|Closure $searchable = false;

    protected string|Closure|null $searchProperty = null;

    public function searchable(string|Closure|null $property = null): static
    {
        $this->setSearchable(true);
        $this->setSearchProperty($property);

        return $this;
    }

    public function setSearchable(bool|Closure|null $searchable): void
    {
        if (is_null($searchable)) {
            return;
        }
        $this->searchable = $searchable;
    }

    public function setSearchProperty(string|Closure|null $property): void
    {
        if (is_null($property)) {
            return;
        }
        $this->searchProperty = $property;
    }

    public function isSearchable(): bool
    {
        return $this->evaluate($this->searchable);
    }

    public function isNotSearchable(): bool
    {
        return ! $this->isSearchable();
    }

    public function getSearchProperty(): ?string
    {
        return $this->evaluate($this->searchProperty);
    }
}
