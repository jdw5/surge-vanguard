<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

trait HasDatabaseQuery
{
    private mixed $query = null;

    /**
     * Define the query to be used for the table.
     * 
     * @param Builder|null $query
     * @return Builder|null
     */
    public function query(mixed $query = null): mixed
    {
        if ($query) $this->query = $query;
        return $this->query;
    }

    /**
     * Check if the table has a query.
     * 
     * @return bool
     */
    public function hasQuery(): bool
    {
        return !\is_null($this->getQuery());
    }

    public function getQuery(): mixed
    {
        return $this->evaluate($this->query);
    }

    protected function setQuery(mixed $query): void
    {
        $this->query = $query;
    }

    public function refineQuery(Collection $refiners): void
    {
        $this->setQuery($this->query->withRefinements($refiners));
    }

    public function isEloquentBuilder(): bool
    {
        return $this->query instanceof EloquentBuilder;
    }

    public function isQueryBuilder(): bool
    {
        return $this->query instanceof QueryBuilder;
    }

    /**
     * Set the query to null, allowing it to be garbage collected.
     */
    public function freeQuery(): void
    {
        $this->query = null;
    }
}