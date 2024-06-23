<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasResource
{
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
}
