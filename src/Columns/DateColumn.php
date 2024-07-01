<?php

namespace Conquest\Table\Columns;

use Carbon\Carbon;
use Closure;
use Conquest\Table\Columns\Concerns\HasFormat;
use Conquest\Table\Columns\Enums\Breakpoint;
use Exception;

class DateColumn extends Column
{
    use HasFormat;

    public function __construct(
        string|Closure $name,
        string|Closure|null $label = null,
        bool $sortable = false,
        bool $searchable = false,
        string|Breakpoint $breakpoint = Breakpoint::NONE,
        bool|Closure|null $authorize = null,
        mixed $fallback = null,
        bool $asHeading = true,
        bool $srOnly = false,
        string|Closure|null $format = null,
        ?Closure $transform = null,
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $breakpoint, $authorize, $fallback, $asHeading, $srOnly, $transform);
        $this->setType('col:date');
        $this->setFormat($format);
    }

    public function apply(mixed $value): mixed
    {
        if ($this->canTransform()) {
            $value = $this->transformUsing($value);
        }

        if (is_null($value)) {
            return $this->getFallback();
        }

        if ($this->hasFormat()) {
            try {
                $value = Carbon::parse($value)->format($this->getFormat());
            } catch (Exception $e) {
                if ($this->hasFallback()) {
                    return $this->getFallback();
                }
            }
        }

        return $value;
    }
}
