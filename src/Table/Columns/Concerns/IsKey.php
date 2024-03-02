<?php

namespace Jdw5\SurgeVanguard\Table\Columns\Concerns;

trait IsKey
{
    protected bool $key = false;

    public function isKey(): bool
    {
        return $this->key;
    }

    public function key(): static
    {
        $this->key = true;
        return $this;
    }

    // Alias
    public function asKey(): static
    {
        return $this->key();
    }
}