<?php

namespace Jdw5\Vanguard\Sorts;

use Closure;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Sorts\Concerns\HasDirection;

/** Must have order key, but order key itself does not matter */
class AnisotropicSort extends BaseSort
{    
    
}