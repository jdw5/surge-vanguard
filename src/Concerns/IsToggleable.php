<?php

namespace Conquest\Table\Concerns;

trait IsToggleable
{
    protected bool $toggle;

    public function isToggleable(): bool
    {
        if (isset($this->toggle)) {
            return $this->toggle;
        }

        if (method_exists($this, 'toggle')) {
            return $this->toggle();
        }

        return false;
    }
}
