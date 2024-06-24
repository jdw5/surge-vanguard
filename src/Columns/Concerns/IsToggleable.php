<?php

namespace Jdw5\Vanguard\Columns\Concerns;

trait IsToggleable
{
    protected bool $toggleable = true;

    public function toggleable(): static
    {
        $this->setToggleability(true);
        return $this;
    }

    public function notToggleable(): static
    {
        $this->setToggleability(false);
        return $this;
    }

    public function toggle(): static
    {
        return $this->toggleable();
    }

    public function dontToggle(): static
    {
        return $this->notToggleable();
    }
    
    protected function setToggleability(bool $toggleable): void
    {
        $this->toggleable = $toggleable;
    }

    public function toggles(): bool
    {
        return $this->toggleable;
    }
    
    public function isToggleable(): bool
    {
        return $this->isToggleable();
    }
    
}