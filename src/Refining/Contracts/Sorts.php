<?php

namespace Jdw5\Vanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Sorts
{
    /** The default direction to sort in if none is supplied */
    public const DEFAULT_DIRECTION = 'asc';

    /**
     * Apply the sorting to the builder
     * 
     * @param Builder|QueryBuilder $builder
     * @param string $property
     * @param string|null $direction
     * @return void
     */
    public function apply(Builder|QueryBuilder $builder, string $property, string $direction): void;
}