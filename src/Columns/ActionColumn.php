<?php

namespace Jdw5\Vanguard\Columns;

use Closure;
use Jdw5\Vanguard\Enums\Breakpoint;

class ActionColumn extends BaseColumn
{

    public function __construct(
        string $label,
        bool $srOnly = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
    ) {
        parent::__construct($label, $srOnly, $breakpoint);
        $this->setType('col:action');
    }    
}