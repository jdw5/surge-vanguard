<?php

declare(strict_types=1);

namespace Conquest\Table\Filters\Concerns;

use Closure;

trait IsRestricted
{
    protected bool|Closure $restricted = false;

    public function restricted(bool|Closure $restricted = true): static
    {
        $this->setRestricted($restricted);

        return $this;
    }

    public function unrestricted(bool|Closure $restricted = false): static
    {
        $this->setRestricted($restricted);

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
