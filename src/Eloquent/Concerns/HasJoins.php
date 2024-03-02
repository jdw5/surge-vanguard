<?php

namespace Jdw5\SurgeVanguard\Eloquent\Concerns;

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