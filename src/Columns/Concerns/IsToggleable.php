<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait IsToggleable
{
    protected bool|Closure $toggledByDefault = true;

    protected function setToggleByDefault(bool|Closure|null $toggle): void
    {
        if (is_null($toggle)) return;
        $this->toggledByDefault = $toggle;
    }

    public function toggle(bool|Closure $toggle): static
    {
        $this->setToggleByDefault($toggle);
        return $this;
    }

    public function toggleOn(): static
    {
        $this->setToggleByDefault(true);
        return $this;
    }

    public function toggleOff(): static
    {
        $this->setToggleByDefault(false);
        return $this;
    }

    public function isDefaultToggle(): bool
    {
        return $this->evaluate($this->toggledByDefault);
    }
}