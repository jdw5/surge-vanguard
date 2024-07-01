<?php

namespace Conquest\Table\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

interface Filters
{
    /**
     * Apply the filter to the builder
     *
     * @param  string  $property
     * @param  string|null  $direction
     */
    public function apply(Builder|QueryBuilder $builder): void;

    public function filtering(Request $request): bool;
}
