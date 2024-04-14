<?php

namespace Jdw5\Vanguard\Refining\Concerns;

/**
 * Map a query value to a different value to obfuscate the original value.
 */
trait HasMap
{
    protected callable $map = (fn ($value) => $value);

    public function map(callable $map): static
    {
        $this->map = $map;
        return $this;
    }

    public function getMap(): string|callable
    {
        return $this->evaluate($this->map);
    }
}