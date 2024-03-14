<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Filters\InvalidQueryException;

trait HasQuery
{
    protected \Closure $query;

    public function query(\Closure $query): static
    {
        $this->validateQueryClosure($query);
        $this->query = $query;
        return $this;
    }

    protected function validateQueryClosure(\Closure $query): void
    {
        $reflection = new \ReflectionFunction($query);
        $parameters = $reflection->getParameters();

        if (count($parameters) !== 2) {
            throw InvalidQueryException::count(2);
        }

        if (!$parameters[0]->getType() || $parameters[0]->getType()->getName() !== Builder::class) {
            throw InvalidQueryException::invalid();
        }
    }

    public function getQuery(): \Closure
    {
        if (!isset($this->query)) {
            throw InvalidQueryException::missing();
        }
        return $this->query;
    }
}