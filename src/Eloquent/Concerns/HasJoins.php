<?php

namespace Jdw5\Vanguard\Eloquent\Concerns;

trait HasJoins
{
    // protected array $joins = [];

    public function getJoins(): array
    {
        if (isset($this->joins)) {
            return $this->joins;
        }
    }
}