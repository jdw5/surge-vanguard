<?php

namespace Jdw5\SurgeVanguard\Eloquent;

use Jdw5\SurgeVanguard\Eloquent\Concerns\HasSelects;
use Jdw5\SurgeVanguard\Eloquent\Contracts\Relates;

class Relation implements Relates
{
    use HasSelects;

    protected string $table;
}