<?php

namespace Jdw5\Vanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

interface Refines
{
    public function refine(Builder|QueryBuilder $builder, ?Request $request = null): void;
    
    public function isActive(): bool;
}