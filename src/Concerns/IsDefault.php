<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Trait IsDefault
 * 
 * Set whether a class is the default
 * 
 * @property bool $default
 */
trait IsDefault
{
    protected $default = false;

    /**
     * Set the class as default
     * 
     * @return static
     */
    public function default(): static
    {
        $this->default = true;
        return $this;
    }

    /**
     * Check if the class is default
     * 
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->evaluate($this->default);
    }
}