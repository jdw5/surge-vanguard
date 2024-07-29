<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsSortable
{
    protected bool|Closure $sortable = false;
    protected string|Closure|null $sortProperty = null;

    public function sortable(string|Closure $property = null): static
    {
        $this->setSortable(true);
        $this->setSortProperty($property);
        return $this;
    }

    public function setSortable(bool|Closure|null $sortable): void
    {
        if (is_null($sortable)) return;
        $this->sortable = $sortable;
    }

    public function setSortProperty(string|Closure|null $property): void
    {
        if (is_null($property)) return;
        $this->sortProperty = $property;
    }

    public function isSortable(): bool
    {
        return $this->evaluate($this->sortable);
    }

    public function isNotSortable(): bool
    {
        return !$this->isSortable();
    }

    public function getSortProperty(): ?string
    {
        return $this->evaluate(
            value: $this->sortProperty,
        );
    }
}
