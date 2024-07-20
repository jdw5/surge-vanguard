<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasFallback;
use Conquest\Table\Columns\Concerns\SharedConstructor;
use Conquest\Table\Columns\Concerns\SharedCreation;

abstract class FallbackColumn extends BaseColumn
{
    use HasFallback;

    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        mixed $fallback = null,
        bool $hidden = false,
        Closure|bool $authorize = null,
        Closure $transform = null,
        Breakpoint|string $breakpoint = null,
        bool $srOnly = false,
        bool $sortable = false,
        bool $searchable = false,
        bool $active = true,
        bool $key = false,
        array $metadata = null,
    ) {
        parent::__construct(...compact(
            'name',
            'label',
            'hidden',
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
        $this->setFallback($fallback ?? $this->defaultFallback());        
    }

    protected function defaultFallback(): mixed
    {
        return config('table.fallback.default', null);
    }

    /**
     * Convert the column to an array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'fallback' => $this->getFallback(),
        ]);
    }

    public function apply(mixed $value): mixed
    {
        if (is_null($value)) return $this->getFallback();
        return parent::apply($value);
    }
}
