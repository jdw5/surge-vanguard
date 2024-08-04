<?php

namespace Conquest\Table\Filters\Concerns;

use Closure;

trait IsMultiple
{
    protected bool|Closure $multiple = false;

    public function multiple(bool|Closure $restrict = true): static
    {
        $this->setMultiple($restrict);

        return $this;
    }
    
    public function setMultiple(bool|Closure|null $multiple): void
    {
        if (is_null($multiple)) {
            return;
        }
        $this->multiple = $multiple;
    }

    public function isMultiple(): bool
    {
        return (bool) $this->evaluate($this->multiple);
    }

    public function isNotMultiple(): bool
    {
        return ! $this->isMultiple();
    }
}
