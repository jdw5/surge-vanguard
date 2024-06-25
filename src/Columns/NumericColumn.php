<?php

namespace Jdw5\Vanguard\Columns;

use Closure;
use Jdw5\Vanguard\Columns\Enums\Breakpoint;

class DateColumn extends Column
{
    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        string|Breakpoint $breakpoint = Breakpoint::NONE,
        bool|Closure $authorize = null,
        mixed $fallback = 0,
        bool $asHeading = true,
        bool $srOnly = false,
        Closure $transform = null,
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $toggleable, $breakpoint, $authorize, $fallback, $asHeading, $srOnly, $transform);
        $this->setType('col:numeric');
    }
}
