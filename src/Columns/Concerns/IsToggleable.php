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
    
    protected function setToggleability(bool $toggleable): void
    {
        $this->toggleable = $toggleable;
    }
}