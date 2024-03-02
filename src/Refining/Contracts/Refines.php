<?php

namespace Jdw5\SurgeVanguard\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface Refines
{
    public function refine(Builder $builder, ?Request $request = null): void;
    
    public function isActive(): bool;
}