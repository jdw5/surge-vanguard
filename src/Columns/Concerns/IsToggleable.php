<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsToggleable
{
    protected bool|Closure $toggleable = false;

    protected bool|Closure $toggledOn = true;

    public function toggleable(bool|Closure $toggledOn = true): static
    {
        $this->setToggleable(true);
        $this->setToggledOn($toggledOn);

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

    public function setToggledOn(bool|Closure|null $toggledOn): void
    {
        if (is_null($toggledOn)) {
            return;
        }
        $this->toggledOn = $toggledOn;
    }

    public function isToggledOn(): bool
    {
        return $this->evaluate($this->toggledOn);
    }

    public function isNotToggledOn(): bool
    {
        return ! $this->isToggledOn();
    }
}
