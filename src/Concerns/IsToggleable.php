<?php

namespace Conquest\Table\Concerns;

trait IsToggleable
{
    public function isToggleable(): bool
    {
        if (isset($this->toggleable)) {
            return $this->toggleable;
        }

        if (method_exists($this, 'toggleable')) {
            return $this->toggleable();
        }

        return false;
    }
}
