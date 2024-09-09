<?php

namespace Conquest\Table\Concerns;

use Illuminate\Contracts\Database\Query\Builder as BaseContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait HasResource
{
    // BaseContract
    protected $resource = null;
    // protected $modelClass = null;

    protected function setResource($resource): void
    {
        if (is_null($resource)) {
            return;
        }

        if (is_string($resource)) {
            if (class_exists($resource)) {
                $this->resource = $resource;
            } else {
                throw new \InvalidArgumentException("Class {$resource} does not exist.");
            }
        } else {
            $this->resource = $resource;
        }
    }

    public function getResource()
    {
        if (isset($this->resource)) {
            if (is_string($this->resource)) {
                // If it's a string (class name), create a query
                return $this->resource::query();
            }

            // if ($this->resource instanceof \Illuminate\Database\Eloquent\Model) {
            //     return $this->resource->newQuery();
            // }
            return $this->isBuilderInstance() ? $this->resource : $this->resource->query();
        }

        // Check if the resource() function is defined
        if (method_exists($this, 'resource')) {
            return $this->resource();
        }

        // Else, try to resolve a model from name
        $modelClass = str(static::class)
            ->classBasename()
            ->beforeLast('Table')
            ->singular()
            ->prepend('\\App\\Models\\')
            ->toString();

        if (class_exists($modelClass)) {
            return $modelClass::query();
        }

        throw new \RuntimeException('Unable to resolve resource for '.static::class);
    }

    public function getModelClass(): string
    {
        $resource = $this->getResource();

        if ($resource instanceof Builder) {
            return get_class($resource->getModel());
        }

        if ($resource instanceof \Illuminate\Database\Eloquent\Model) {
            return get_class($resource);
        }

        if ($resource instanceof QueryBuilder) {
            return $resource->from;
        }
        throw new \RuntimeException('Unable to get base model for resource');
    }

    public function resolveModel(string $modelClass, string|int $key)
    {
        return $modelClass::where($this->getTableKey(), $key)->first();
    }

    public function isBuilderInstance()
    {
        return $this->resource instanceof Builder || $this->resource instanceof QueryBuilder;
    }
}
