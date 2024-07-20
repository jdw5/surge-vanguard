<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Enums\Breakpoint;

class Column extends BaseColumn
{
    public function setUp(): void
    {
        $this->setType('col');
    }
    
    public static function make(
        string|Closure $name, 
        string|Closure $label = null,
        bool $hidden = false,
        mixed $fallback = null,
        Closure|bool $authorize = null,
        Closure $transform = null,
        Breakpoint|string $breakpoint = null,
        bool $srOnly = false,
        bool $sortable = false,
        bool $searchable = false,
        bool $active = true,
        bool $isKey = false,
        array $metadata = null,
    ): static {
        return resolve(static::class, compact(
            'name',
            'label',
            'hidden',
            'fallback',
            'authorize',
            'transform',
            'breakpoint',
            'srOnly',
            'sortable',
            'searchable',
            'active',
            'isKey',
            'metadata',
        ));
    }}
