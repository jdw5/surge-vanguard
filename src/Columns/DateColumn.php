<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Exception;
use Carbon\Carbon;
use Conquest\Table\Columns\Concerns\HasFormat;
use Conquest\Table\Concerns\Formatters\FormatsSince;

class DateColumn extends FallbackColumn
{
    use HasFormat;
    use FormatsSince;

    public function setUp(): void
    {
        $this->setType('col:date');
    }

    public function apply(mixed $value): mixed
    {
        if (is_null($value)) return $this->getFallback();
        
        $value = $this->applyTransform($value);

        if ($this->isSince()) {
            try {
                return $this->formatSince($value);
            } catch (Exception $e) {
                if ($this->hasFallback()) return $this->getFallback();
            }
        }
        
        if ($this->hasFormat()) {
            try {
                $value = Carbon::parse($value)->format($this->getFormat());
            } catch (Exception $e) {
                if ($this->hasFallback()) return $this->getFallback();
            }
        }
        return $this->formatValue($value);
    }
}
