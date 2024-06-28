<?php

namespace Conquest\Table\Sorts;

use Closure;
use Illuminate\Http\Request;
use Conquest\Table\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Conquest\Table\Sorts\Concerns\HasDirection;

/** Must have order key, but order key itself does not matter */
class IsotropicSort extends BaseSort
{    
    
}