<?php

namespace Jdw5\Vanguard\Concerns;

trait IsActive
{
    protected bool $active = false;

    public function active(bool $active = true): static
    {
        $this->active = $active;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}