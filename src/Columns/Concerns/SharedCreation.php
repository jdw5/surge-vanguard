<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;


trait SharedCreation
{
    public static function make(
        string|Closure $name, 
        string|Closure $label = null,
        bool $hidden = false,
        mixed $fallback = null,
        Closure|bool $authorize = null,
        Closure $transform = null,
        string $breakpoint = null,
        bool $srOnly = false,
        bool $sortable = false,
        bool $searchable = false,
        bool $active = true,
        bool $key = false,
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
            'key',
            'metadata',
        ));
    }
}
