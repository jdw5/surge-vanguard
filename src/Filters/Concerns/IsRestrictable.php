<?php

namespace Jdw5\Vanguard\Filters\Concerns;

trait IsRestrictable
{
    protected bool $restricted = false;

    public function restrict(): static
    {
        $this->setRestricted(true);
        return $this;
    }

    public function unrestricted(): static
    {
        $this->setRestricted(false);
        return $this;
    }

    public function restrictToOptions(): static
    {
        return $this->restrict();
    }

    public function dontRestrictToOptions(): static
    {
        return $this->unrestricted();
    }

    protected function setRestricted(bool $restricted): void
    {
        if (is_null($restricted)) return;
        $this->restrictions = $restricted;
    }

    public function getRestriction(): bool
    {
        return $this->evaluate($this->restrictions);
    }

    public function isRestricted(): bool
    {
        return $this->getRestriction();
    }
}