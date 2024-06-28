<?php

namespace Conquest\Table\Columns\Concerns;

/**
 * Set a fallback/backup property for a class.
 */
trait HasFallback
{
    protected mixed $fallback = null;

    /**
     * Set the fallback value, chainable.
     * 
     * @param mixed $fallback
     * @return static
     
     */
    public function fallback(mixed $fallback): static
    {
        $this->setFallback($fallback);
        return $this;
    }

    public function ifNull(mixed $fallback): static
    {
        return $this->fallback($fallback);
    }

    /**
     * Set the fallback value quietly.
     * 
     * @param mixed $fallback
     * @return void
     */
    protected function setFallback(mixed $fallback): void
    {
        $this->fallback = $fallback;
    }

    /**
     * Check if a fallback value exists.
     * 
     * @return bool
     */
    public function hasFallback(): bool
    {
        return !is_null($this->fallback);
    }

    /**
     * Get the fallback value.
     * 
     * @return mixed
     */
    public function getFallback(): mixed
    {
        return $this->evaluate($this->fallback);
    }
}