<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

trait HasFallback
{
    protected mixed $fallback = null;

    public function fallback(mixed $fallback): static
    {
        $this->fallback = $fallback;
        return $this;
    }

    public function getFallback(): mixed
    {
        return $this->fallback;
    }
}