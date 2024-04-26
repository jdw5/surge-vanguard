<?php

namespace Jdw5\Vanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Filters
{
    /**
     * Apply the filtering to the builder
     * 
     * @param Builder|QueryBuilder $builder
     * @param string $property
     * @param mixed $value
     * @return void
     */
    public function apply(Builder|QueryBuilder $builder, string $property, mixed $value): void;
}