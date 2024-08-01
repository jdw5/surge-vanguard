<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Exception;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Conquest\Table\Columns\Concerns\HasFormat;
use Conquest\Table\Columns\Concerns\Formatters\FormatsSince;

class DateColumn extends FallbackColumn
{
    use FormatsSince;
    use HasFormat;

    public function setUp(): void
    {
        $this->setType('col:date');
    }

    public function apply(mixed $value): mixed
    {
        if (is_null($value)) {
            return $this->getFallback();
        }

        if ($this->isSince()) {
            try {
                return $this->formatSince($value);
            } catch (InvalidFormatException $e) {
                if ($this->hasFallback()) {
                    return $this->getFallback();
                }
            }
        }

        if ($this->hasFormat()) {
            try {
                $value = Carbon::parse($value)->format($this->getFormat());
            } catch (InvalidFormatException $e) {
                if ($this->hasFallback()) {
                    return $this->getFallback();
                }
            }
        }

        return $this->formatValue($value);
    }
}
