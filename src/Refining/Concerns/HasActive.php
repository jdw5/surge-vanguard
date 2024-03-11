<?php

namespace Jdw5\Vanguard\Refining\Concerns;

trait HasActive
{
    
    public function isActive(): bool
    {
        return !is_null($this->getValue());
    }
}