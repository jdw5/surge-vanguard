<?php

namespace Jdw5\SurgeVanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Sorts
{
    public const DEFAULT_DIRECTION = 'asc';

    public function apply(Builder $builder, string $property, ?string $direction): void;
}