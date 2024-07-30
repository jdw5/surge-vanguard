<?php

declare(strict_types=1);

namespace Conquest\Table\Concerns\Formatters;

use Closure;
use Conquest\Table\Columns\Concerns\Formatters\CanSetDecimalPlaces;
use Conquest\Table\Columns\Concerns\Formatters\CanSetDivideBy;
use Conquest\Table\Columns\Concerns\Formatters\CanSetLocale;
use Conquest\Table\Columns\Concerns\Formatters\CanSetRoundToNearest;

trait FormatsNumeric
{
    use CanSetDivideBy;
    use CanSetDecimalPlaces;
    use CanSetRoundToNearest;
    use CanSetLocale;

    protected bool $numeric = false;

    public function numeric(int|Closure $decimalPlaces = null, int|Closure $roundedToNearest = null, string|Closure $locale = null, int|Closure $divideBy = null): static
    {
        $this->numeric = true;
        $this->setDecimalPlaces($decimalPlaces);
        $this->setRoundToNearest($roundedToNearest);
        $this->setDivideBy($divideBy);
        $this->setLocale($locale);
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
        if ($this->isNotNumeric()) return $value;
        if (!is_numeric($value)) return $value;
        
        if ($this->hasDivideBy()) {
            $value = $value / $this->getDivideBy();
        }

        if ($this->hasRoundToNearest()) {
            $value = round($value / $this->getRoundToNearest()) * $this->getRoundToNearest();
        }

        if ($this->hasDecimalPlaces()) {
            $value = number_format($value, $this->getDecimalPlaces(), '.', ',');
        }

        return $value;
    }
}
