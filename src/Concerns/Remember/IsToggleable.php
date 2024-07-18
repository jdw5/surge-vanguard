<?php

namespace Conquest\Table\Concerns\Remember;

trait IsToggleable
{
    protected bool $toggledOn = true;
    
    public function isToggledOn(): bool
    {
        return $this->toggledOn;
    }

    public function off(): static
    {
        $this->setToggleability(false);
        return $this;
    }

    public function on(): static
    {
        $this->setToggleability(true);
        return $this;
    }

    public function setToggleability(bool|null $toggle): void
    {
        if (is_null($toggle)) return;
        $this->toggledOn = $toggle;
    }
}
