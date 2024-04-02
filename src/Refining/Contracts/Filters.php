<?php

namespace Jdw5\Vanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Filters
{
    public function apply(Builder|QueryBuilder $builder, string $property, mixed $value): void;
}