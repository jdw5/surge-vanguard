<?php

namespace Conquest\Table\Filters\Concerns;

use Closure;

trait IsRestricted
{
    protected bool|Closure $restricted = false;

    public function restricted(bool|Closure $restrict = true): static
    {
        $this->setRestricted($restrict);

        return $this;
    }

    public function unrestricted(bool|Closure $restrict = false): static
    {
        $this->setRestricted($restrict);

        return $this;
    }

    public function setRestricted(bool|Closure|null $restricted): void
    {
        if (is_null($restricted)) {
            return;
        }
        $this->restricted = $restricted;
    }

    public function isRestricted(): bool
    {
        return (bool) $this->evaluate($this->restricted);
    }

    public function isNotRestricted(): bool
    {
        return ! $this->isRestricted();
    }
}
