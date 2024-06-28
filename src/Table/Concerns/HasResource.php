<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Database\Eloquent\Builder ;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait HasResource
{
    protected mixed $resource = null;

    protected function setResource(Builder|QueryBuilder|null $resource): void
    {
        if (is_null($resource)) return;
        $this->resource = $resource;
    }

    public function getResource()
    {
        if (isset($this->resource)) {
            return $this->isBuilderInstance() ? $this->resource : $this->resource->query();
        }

        // Check if the resource() function is defined
        if (method_exists($this, 'resource')) {
            return $this->resource();
        }

        // Else, try to resolve a model from name
        return (str(static::class)
            ->classBasename()
            ->beforeLast('Table')
            ->singular()
            ->prepend('\\App\\Models\\')
            ->toString())::query();
    }

    public function getBaseModel()
    {
        // Even if given a query, find the model or DB::table

        // If the resource is a query, get the model
        if (is_a($this->getResource(), Builder::class)) {
            return $this->getResource()->getModel();
        }

        // If the resource is a model, return it
        if (is_a($this->getResource(), \Illuminate\Database\Eloquent\Model::class)) {
            return $this->getResource();
        }

        // If it's a DB object (QueryBuilder), get the table and return DB::table('string')
    }

    public function isBuilderInstance()
    {
        return $this->resource instanceof Builder || $this->resource instanceof QueryBuilder;
    }
}
