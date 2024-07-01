<?php

namespace Conquest\Table\Sorts\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

interface Sorts
{
    /** The default direction to sort in if none is supplied */
    public const DEFAULT_DIRECTION = 'asc';

    /**
     * Apply the sorting to the builder
     *
     * @param  string  $property
     * @param  string|null  $direction
     */
    public function apply(Builder|QueryBuilder $builder): void;

    public function sorting(Request $request): bool;
}
