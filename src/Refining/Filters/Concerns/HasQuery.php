<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

trait HasQuery
{
    protected \Closure $query;

    public function query(\Closure $query): static
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): \Closure
    {
        return $this->query;
    }
}