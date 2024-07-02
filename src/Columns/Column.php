<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Enums\Breakpoint;

class Column extends BaseColumn
{

    /**
     * Statically create the column
     */
    public static function make(
        string $name, 
        string $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
        Closure|bool $authorize = true,
        mixed $fallback = null,
        bool $asHeading = false,
        bool $srOnly = false,
        Closure $transform = null,
        bool $active = true,
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
            'asHeading', 
            'srOnly', 
            'transform',
            'active',
        ));
    }}