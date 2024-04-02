<?php

namespace Jdw5\Vanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Sorts
{
    public const DEFAULT_DIRECTION = 'asc';

    public function apply(Builder|QueryBuilder $builder, string $property, ?string $direction): void;
}