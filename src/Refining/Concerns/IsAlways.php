<?php

namespace Jdw5\Vanguard\Refining\Concerns;

trait IsAlways
{
    protected bool $always = false;

    public function always(bool $always = true): static
    {
        $this->always = $always;
        return $this;
    }

    public function isAlways(): bool
    {
        return $this->always;
    }
}