<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait FormatsNumeric
{
    use CanSetDecimalPlaces;
    use CanSetDivideBy;
    use CanSetLocale;
    use CanSetRoundToNearest;

    protected bool $numeric = false;

    public function numeric(int|Closure|null $decimalPlaces = null, int|Closure|null $roundedToNearest = null, string|Closure|null $locale = null, int|Closure|null $divideBy = null): static
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
        return ! $this->isNumeric();
    }

    public function formatNumeric(mixed $value)
    {
        if (! is_numeric($value)) {
            return $value;
        }

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
