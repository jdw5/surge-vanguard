<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Enums\Breakpoint;

class NumericColumn extends BaseColumn
{
    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        string|Breakpoint $breakpoint = Breakpoint::NONE,
        bool|Closure $authorize = null,
        mixed $fallback = 0,
        bool $asHeading = true,
        bool $srOnly = false,
        Closure $transform = null,
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $breakpoint, $authorize, $fallback, $asHeading, $srOnly, $transform);
        $this->setType('col:numeric');
    }

    public static function make(
        string $name, 
        string $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
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
            'format'
        ));
    }
}
