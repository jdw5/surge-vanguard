<?php

namespace Jdw5\Vanguard\Columns\Concerns;

trait IsKey
{
    protected bool $key = false;

    /**
     * Check if the column is the key
     * 
     * @return bool
     */
    public function isKey(): bool
    {
        return $this->key;
    }

    /**
     * Set the column as the key
     * 
     * @return static
     */
    public function key(): static
    {
        $this->key = true;
        return $this;
    }

    /**
     * Alias for key
     * 
     * @return static
     */
    public function asKey(): static
    {
        return $this->key();
    }
}