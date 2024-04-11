<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait HasQuery
{
    private mixed $query = null;

    /**
     * Define the query to be used for the table.
     * 
     * @param Builder|null $query
     * @return Builder|null
     */
    public function query(EloquentBuilder|QueryBuilder $query = null): mixed
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
        return !\is_null($this->query);
    }

    public function getQuery(): EloquentBuilder|QueryBuilder
    {
        return $this->query;
    }
}