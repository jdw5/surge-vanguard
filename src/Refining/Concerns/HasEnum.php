<?php

namespace Jdw5\Vanguard\Refining\Concerns;

use Closure;

trait HasEnum
{
    protected null|string|Closure $enum = null;

    public function enum(string $enum): static
    {
        $this->enum = $enum;
        return $this;
    }

    public function getEnumClass(): ?string
    {
        return $this->evaluate($this->enum);
    }
}