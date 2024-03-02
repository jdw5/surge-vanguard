<?php

namespace Jdw5\SurgeVanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Filters
{
    public function apply(Builder $builder, mixed $value, string $property): void;
}