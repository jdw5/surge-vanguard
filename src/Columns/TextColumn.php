<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\BaseColumn;
use Conquest\Table\Columns\Enums\Breakpoint;

class TextColumn extends BaseColumn
{
    public function setUp(): void
    {
        $this->setType('col:text');
    }
    
    public static function make(
        string $name, 
        string $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        Breakpoint|string $breakpoint = null,
        Closure|bool $authorize = null,
        mixed $fallback = '-',
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
