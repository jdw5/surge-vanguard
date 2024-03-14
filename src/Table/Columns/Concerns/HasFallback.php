<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

/**
 * Trait HasFallback
 * 
 * Set a fallback/backup property for a column
 * 
 * @property mixed $fallback
 */
trait HasFallback
{
    protected mixed $fallback = null;

    /**
     * Set the fallback value for the column
     * 
     * @param mixed $fallback
     * @return static
     
     */
    public function fallback(mixed $fallback): static
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * Check if the column has a fallback value
     * 
     * @return bool
     */
    public function hasFallback(): bool
    {
        return !\is_null($this->fallback);
    }

    /**
     * Get the fallback value for the column
     * 
     * @return mixed
     */
    public function getFallback(): mixed
    {
        return $this->fallback;
    }
}