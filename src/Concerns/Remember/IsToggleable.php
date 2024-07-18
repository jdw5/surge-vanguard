<?php

namespace Conquest\Table\Concerns\Remember;

trait IsToggleable
{
    protected $on;
    
    public function isToggledOn(): bool
    {
        if (!isset($this->on)) {
            return true;
        }
        return false;
    }

    public function off(): static
    {
        $this->on = false;
        return $this;
    }
}
