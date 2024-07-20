<?php

namespace Conquest\Table\Concerns\Remember;

trait IsToggleable
{
    protected bool $toggledOn = true;
    
    public function isActive(): bool
    {
        return $this->toggledOn;
    }

    public function off(): static
    {
        $this->setToggledOn(false);
        return $this;
    }

    public function on(): static
    {
        $this->setToggledOn(true);
        return $this;
    }

    public function setToggledOn(bool|null $toggle): void
    {
        if (is_null($toggle)) return;
        $this->toggledOn = $toggle;
    }
}
