<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Trait IsActive
 * 
 * Set an active property on a class
 * 
 * @property bool $active
 */
trait IsActive
{
    protected bool $active = false;

    /**
     * Set the active property
     * 
     * @param bool $active
     * @return static
     */
    public function active(bool $active = true): static
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Check if the class is active
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}