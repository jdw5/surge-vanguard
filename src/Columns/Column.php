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
        Closure|bool $authorize = null,
        mixed $fallback = null,
        bool $asHeading = true,
        bool $srOnly = false,
        Closure $transform = null,
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
            'transform'
        ));
    }}