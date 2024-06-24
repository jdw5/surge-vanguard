<?php

namespace Jdw5\Vanguard\Filters\Contracts;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Filters
{
    /**
     * Apply the filter to the builder
     * 
     * @param Builder|QueryBuilder $builder
     * @param string $property
     * @param string|null $direction
     * @return void
     */
    public function apply(Builder|QueryBuilder $builder): void;
    public function filtering(Request $request): bool;

}