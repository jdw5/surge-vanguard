<?php

namespace Jdw5\Vanguard\Concerns;

trait IsIncludable
{
    protected bool|\Closure $isExcluded = false;
    protected bool|\Closure $isIncluded = true;

    public function exclude(bool|\Closure $condition = true): static
    {
        $this->isExcluded = $condition;

        return $this;
    }

    public function include(bool|\Closure $condition = true): static
    {
        $this->isIncluded = $condition;
        return $this;
    }

    public function isExcluded(): bool
    {
        if ($this->evaluate($this->isExcluded)) {
            return true;
        }

        return !$this->evaluate($this->isIncluded);
    }
}
