<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\BaseColumn;
use Conquest\Table\Columns\Enums\Breakpoint;

class TextColumn extends BaseColumn
{
    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        string|Breakpoint $breakpoint = Breakpoint::NONE,
        bool|Closure $authorize = null,
        mixed $fallback = '-',
        bool $hidden = false,
        bool $srOnly = false,
        Closure $transform = null,
        bool $active = true,
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $breakpoint, $authorize, $fallback, $hidden, $srOnly, $transform, $active);
        $this->setType('col:text');
    }

    public static function make(
        string $name, 
        string $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
        Closure|bool $authorize = null,
        mixed $fallback = null,
        bool $hidden = false,
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
            'hidden', 
            'srOnly', 
            'transform',
            'active'
        ));
    }
}
