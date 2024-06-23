<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Database\Query\Builder;

trait HasResource
{
    protected $resource;

    protected function setResource(array|null $resource): void
    {
        if (is_null($resource)) return;
        $this->resource = $resource;
    }

    public function getResource()
    {
        if (isset($this->resource)) {
            return $this->resource;
        }

        // Check if the resource() function is defined
        if (function_exists('resource')) {
            return $this->resource();
        }

        // Else, try to resolve a model from name
        return str(static::class)
            ->classBasename()
            ->beforeLast('Table')
            ->singular()
            ->prepend('\\App\\Models\\')
            ->toString();
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
}
