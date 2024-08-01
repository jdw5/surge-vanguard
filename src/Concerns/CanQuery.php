<?php

namespace Conquest\Table\Concerns;

use Closure;
use Conquest\Table\Exceptions\QueryNotDefined;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait HasQuery
{
    protected ?Closure $query = null;

    public function query(Closure $query): static
    {
        $this->setQuery($query);

        return $this;
    }

    protected function setQuery(?Closure $query): void
    {
        if (is_null($query)) {
            return;
        }
        $this->query = $query;
    }

    public function getQuery(): Closure
    {
        if (! isset($this->query)) {
            throw new QueryNotDefined;
        }

        return $this->query;
    }

    public function lacksQuery(): bool
    {
        return is_null($this->query);
    }

    public function hasQuery(): bool
    {
        return ! $this->lacksQuery();
    }

    public function applyQuery(Builder|QueryBuilder $builder)
    {
        if ($this->lacksQuery()) {
            return $builder;
        }

        return $this->evaluate(
            $this->query,
            $builder
        );
    }
}
