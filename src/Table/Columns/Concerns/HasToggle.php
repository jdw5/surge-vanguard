<?php

namespace Jdw5\Vanguard\Table\Concerns\Columns;

trait HasToggle
{
    protected bool $toggle = false;
    protected bool $default = false;
    
    public function toggle(bool $default = false): static
    {
        $this->toggle = true;
        $this->default = $default;
        return $this;
    }

    public function isToggleable(): bool
    {
        return $this->toggle;
    }

    public function getDefault(): bool
    {
        return $this->default;
    }
}