<?php

namespace Jdw5\Vanguard\Eloquent;

use Jdw5\Vanguard\Eloquent\Concerns\HasSelects;
use Jdw5\Vanguard\Eloquent\Contracts\Relates;

class Relation implements Relates
{
    use HasSelects;

    protected string $table;
}