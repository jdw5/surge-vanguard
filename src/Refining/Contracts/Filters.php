<?php

namespace Jdw5\Vanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Filters
{
    public function apply(Builder $builder, string $property, mixed $value): void;
}