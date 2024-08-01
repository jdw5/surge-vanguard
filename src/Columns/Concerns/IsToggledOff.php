<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsToggledOff
{
    // All columns are toggled on by default
    protected bool|Closure $toggledOff = true;

    public function off(bool|Closure $off = true): static
    {
        $this->setToggledOff($off);
        return $this;
    }

    public function setToggledOff(bool|Closure|null $toggledOff): void
    {
        if (is_null($toggledOff)) return;
        $this->toggledOff = $toggledOff;
    }

    public function isToggledOff(): bool
    {
        return $this->evaluate($this->toggledOff);
    }

    public function isNotToggledOff(): bool
    {
        return !$this->isToggledOff();
    }
}