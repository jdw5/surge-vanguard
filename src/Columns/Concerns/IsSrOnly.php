<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsSrOnly
{
    protected bool|Closure $srOnly = false;
    
    public function srOnly(bool|Closure $srOnly = true): static
    {
        $this->setSrOnly($srOnly);
        return $this;
    }

    public function setSrOnly(bool|Closure|null $srOnly): void
    {
        if (is_null($srOnly)) return;
        $this->srOnly = $srOnly;
    }

    public function isSrOnly(): bool
    {
        return (bool) $this->evaluate($this->srOnly);
    }

    public function isNotSrOnly(): bool
    {
        return !$this->isSrOnly();
    }
}

