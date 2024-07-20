<?php

namespace Conquest\Table\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

interface Filters
{
    public function apply(Builder|QueryBuilder $builder): void;
    public function handle(Builder|QueryBuilder $builder): void;
    public function filtering(Request $request): bool;
    public function getValueFromRequest(): mixed;
}
