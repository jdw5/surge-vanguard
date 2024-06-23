<?php

namespace Jdw5\Vanguard\Table\Concerns\Internal;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

trait HasBuilder
{
    private mixed $builder = null;

    /**
     * Define the query to be used for the table.
     * 
     * @param EloquentBuilder|QueryBuilder|null $query
     * @return EloquentBuilder|QueryBuilder|null
     */
    public function setBuilder($builder = null): void
    {
        if (!\is_null($builder)) $this->_setBuilder($builder);
    }

    /**
     * Check if the table has a query.
     * 
     * @return bool
     */
    public function hasBuilder(): bool
    {
        return !\is_null($this->getBuilder());
    }

    public function getBuilder(): mixed
    {
        return $this->builder;
    }

    protected function _setBuilder(EloquentBuilder|QueryBuilder $builder): void
    {
        $this->builder = $builder;
    }

    public function refineBuilder(Collection $refiners): void
    {
        $this->setBuilder($this->builder->withRefinements($refiners));
    }

    public function isEloquentBuilder(): bool
    {
        return $this->builder instanceof EloquentBuilder;
    }

    public function isQueryBuilder(): bool
    {
        return $this->builder instanceof QueryBuilder;
    }

    /**
     * Set the query to null, allowing it to be garbage collected.
     */
    public function freeBuilder(): void
    {
        $this->builder = null;
    }
}