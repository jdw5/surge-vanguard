<?php

namespace Conquest\Table\Columns;

use Closure;
use Exception;
use Carbon\Carbon;
use Conquest\Table\Columns\Column;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasFormat;

class DateColumn extends BaseColumn
{
    use HasFormat;

    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        string|Breakpoint $breakpoint = Breakpoint::NONE,
        bool|Closure $authorize = null,
        mixed $fallback = null,
        bool $hidden = false,
        bool $srOnly = false,
        Closure $transform = null,
        string|Closure $format = null,
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $breakpoint, $authorize, $fallback, $hidden, $srOnly, $transform);
        $this->setType('col:date');
        $this->setFormat($format);
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

    public function apply(mixed $value): mixed
    {
        if ($this->canTransform()) $value = $this->transformUsing($value);

        if (is_null($value)) return $this->getFallback();

        if ($this->hasFormat()) {
            try {
                $value = Carbon::parse($value)->format($this->getFormat());
            } catch (Exception $e) {
                if ($this->hasFallback()) return $this->getFallback();
            }
        }
        return $value;
    }
}
