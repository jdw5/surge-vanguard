<?php

namespace Jdw5\Vanguard\Eloquent\Concerns;

trait HasTable
{
    protected string $table;

    public function getTable(): string
    {
        return $this->table;
    }

    
}