<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;

trait CanBeInline
{
    protected bool|Closure|null $inline = false;

    public function inline(bool|Closure $inline = true): static
    {
        $this->setInline($inline);
        return $this;
    }

    public function setInline(bool|Closure|null $inline): void
    {
        if (is_null($inline)) return;
        $this->inline = $inline;
    }

    public function isInline(): bool
    {
        return $this->evaluate($this->inline);
    }

    public function isNotInline(): bool
    {
        return !$this->isInline();
    }
}