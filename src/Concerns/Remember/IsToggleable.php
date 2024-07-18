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
        $this->setToggleable(false);
        return $this;
    }

    public function on(): static
    {
        $this->setToggleable(true);
        return $this;
    }

    public function setToggleable(bool|null $toggleable): void
    {
        if (is_null($toggleable)) return;
        $this->on = $toggleable;
    }
}
