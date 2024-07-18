<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Enums\Breakpoint;

class NumericColumn extends BaseColumn
{

    public function setUp(): void
    {
        $this->setType('col:numeric');
    }
    
    public static function make(
        string $name, 
        string $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        Breakpoint|string $breakpoint = null,
        Closure|bool $authorize = null,
        mixed $fallback = 0,
        bool $hidden = false,
        bool $srOnly = false,
        Closure $transform = null,
        string|Closure $format = null,
    ): static {
        return resolve(static::class, compact(
            'name', 
            'label', 
            'sortable', 
            'searchable', 
            'toggleable', 
            'breakpoint', 
            'authorize', 
            'fallback', 
            'hidden', 
            'srOnly', 
            'transform',
            'format',
        ));
    }
}
