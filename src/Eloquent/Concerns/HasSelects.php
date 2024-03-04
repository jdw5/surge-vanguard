<?php

namespace Jdw5\Vanguard\Eloquent\Concerns;

trait HasSelects
{
    protected array $selects = [];

    public function getSelects(): array
    {
        return $this->selects;
    }

    public function selects(array|string $selects): static
    {
        if (is_array($selects)) {
            $this->selects = array_merge($this->selects, $selects);
        } else {
            $this->selects[] = $selects;
        }
        return $this;
    }

    public function hasSelects(): bool
    {
        return !empty($this->selects);
    }
}