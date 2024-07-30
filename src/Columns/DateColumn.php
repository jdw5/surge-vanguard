<?php

namespace Conquest\Table\Columns;

use Closure;
use Exception;
use Carbon\Carbon;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasFormat;

class DateColumn extends FallbackColumn
{
    use HasFormat;

    public function setUp(): void
    {
        $this->setType('col:date');
    }

    public function __construct(
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
        bool $key = false,
        string|Closure $format = null,
        array $meta = null,
    ) {
        parent::__construct(...compact(
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
            'meta',
        ));
        $this->setFormat($format);
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
        bool $key = false,
        string|Closure $format = null,
        array $meta = null,
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
            'format',
            'meta',
        ));
    }

    public function apply(mixed $value): mixed
    {
        if (is_null($value)) return $this->getFallback();
        
        if ($this->canTransform()) $value = $this->transformUsing($value);

        if ($this->hasFormat()) {
            try {
                $value = Carbon::parse($value)->format($this->getFormat());
            } catch (Exception $e) {
                if ($this->hasFallback()) return $this->getFallback();
            }
        }
        return $this->modifyAsString($value);
    }
}
