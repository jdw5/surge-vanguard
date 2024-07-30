<?php

declare(strict_types=1);

namespace Conquest\Table\Concerns\Formatters;

use Closure;
use Conquest\Table\Columns\Concerns\Formatters\CanSetDivideBy;

trait FormatsNumeric
{
    use CanSetDivideBy;

    protected bool $numeric = false;

    public function numeric(int|Closure $decimalPlaces = null, int|Closure $divideBy = null): static
    {
        $this->numeric = true;
        $this->setDivideBy($divideBy);
        $this->setDecimalPlaces($decimalPlaces);
        return $this;
    }
    
    public function isNumeric(): bool
    {
        return $this->numeric;
    }

    public function isNotNumeric(): bool
    {
        return  ! $this->isNumeric();
    }

    public function formatNumeric(mixed $value)
    {
        if ($this->isNumeric()) {
            return number_format($value);
        }

        return $value;
    }
}
