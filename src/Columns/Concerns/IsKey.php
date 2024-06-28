<?php

namespace Conquest\Table\Columns\Concerns;

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

    public function isPrimaryKey(): bool
    {
        return $this->isKey();
    }

    /**
     * Set the column as the key
     * 
     * @return static
     */
    public function key(): static
    {
        $this->setKey(true);
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

    public function primaryKey(): static
    {
        return $this->key();
    }

    public function notKey(): static
    {
        $this->setKey(false);
        return $this;
    }

    public function notPrimaryKey(): static
    {
        return $this->notKey();
    }

    public function notAsKey(): static
    {
        return $this->notKey();
    }

    protected function setKey(bool $key): void
    {
        $this->key = $key;
    }
}