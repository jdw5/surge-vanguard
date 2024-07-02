<?php

namespace Conquest\Table\Concerns;

trait HasToggleability
{
    protected bool $toggleable;

    public function getToggleability(): bool
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