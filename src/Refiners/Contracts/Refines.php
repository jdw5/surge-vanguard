<?php

namespace Conquest\Table\Refining\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

interface Refines
{
    /**
     * Verify whether the refinement is active and apply it.
     *
     * @throws \Exception
     */
    public function refine(Builder|QueryBuilder $builder, ?Request $request = null): void;

    /**
     * Check if the refinement is active
     */
    public function isActive(): bool;
}
