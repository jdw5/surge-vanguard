<?php

namespace Conquest\Table\Columns;

use Carbon\Carbon;
use Closure;
use Exception;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasFormat;

class DateColumn extends Column
{
    use HasFormat;

    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        string|Breakpoint $breakpoint = Breakpoint::NONE,
        bool|Closure $authorize = null,
        mixed $fallback = null,
        bool $asHeading = true,
        bool $srOnly = false,
        string|Closure $format = null,
        Closure $transform = null,
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $toggleable, $breakpoint, $authorize, $fallback, $asHeading, $srOnly, $transform);
        $this->setType('col:date');
        $this->setFormat($format);
    }

    public function apply(mixed $value): mixed
    {
        if ($this->hasTransform()) $value = $this->transformUsing($value);

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
