<?php

namespace Conquest\Table\Filters\Concerns;

trait IsRestrictable
{
    protected bool $restricted = false;

    public function restrict(bool $restrict = true): static
    {
        $this->setRestricted($restrict);
        return $this;
    }

    public function unrestricted(bool $restrict = false): static
    {
        $this->setRestricted($restrict);
        return $this;
    }

    protected function setRestricted(?bool $restricted): void
    {
        if (is_null($restricted)) return;
        $this->restricted = $restricted;
    }
    
    public function isRestricted(): bool
    {
        return $this->evaluate($this->restricted);
    }

    public function isNotRestricted(): bool
    {
        return  ! $this->isRestricted();
    }
}
