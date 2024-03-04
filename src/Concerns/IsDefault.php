<?php

namespace Jdw5\Vanguard\Concerns;

trait IsDefault
{
    protected $default = false;

    public function default()
    {
        $this->default = true;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }
}