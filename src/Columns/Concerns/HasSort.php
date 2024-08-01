<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Sorts\ToggleSort;

trait HasSort
{
    protected ?ToggleSort $sort = null;

    public function sort(?string $property = null, ?string $name = null): static
    {
        $this->setSort($property, $name);

        return $this;
    }

    protected function setSort(?string $property = null, ?string $name = null)
    {
        $this->sort = ToggleSort::make($property ?? $this->getName(), $name ?? $this->getName());
    }

    public function sortable(?string $name = null, ?string $property = null): static
    {
        return $this->sort($name, $property);
    }

    public function hasSort(): bool
    {
        return ! is_null($this->getSort());
    }

    public function getSort(): ?ToggleSort
    {
        return $this->sort;
    }

    public function isSorting(): bool
    {
        return (bool) $this->getSort()?->isActive();
    }
}
