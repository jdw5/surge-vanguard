<?php

namespace Conquest\Table\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Sorts
{
    public function apply(Builder|QueryBuilder $builder, ?string $sortBy = null, ?string $direction = null): void;

    public function handle(Builder|QueryBuilder $builder, ?string $direction = null): void;

    public function sorting(?string $sortBy, ?string $direction): bool;
}
