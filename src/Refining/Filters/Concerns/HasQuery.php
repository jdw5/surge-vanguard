<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

use Jdw5\Vanguard\Refining\Filters\InvalidQueryException;

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
        if (!isset($this->query)) {
            throw InvalidQueryException::invalid();
        }
        return $this->query;
    }
}