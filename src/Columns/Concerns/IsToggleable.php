<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsToggleable
{
    protected bool|Closure $toggleable = false;

    public function toggleable(bool|Closure $toggleable = true): static
    {
        $this->setToggleable($toggleable);

        return $this;
    }

    public function setToggleable(bool|Closure|null $toggleable): void
    {
        if (is_null($toggleable)) {
            return;
        }
        $this->toggleable = $toggleable;
    }

    public function isToggleable(): bool
    {
        return $this->evaluate($this->toggleable);
    }

    public function isNotToggleable(): bool
    {
        return ! $this->isToggleable();
    }
}