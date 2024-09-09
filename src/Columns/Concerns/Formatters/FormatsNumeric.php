<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;
use Illuminate\Support\Number;

trait FormatsNumeric
{
    use CanSetDecimalPlaces;
    use CanSetDivideBy;
    use CanSetLocale;
    use CanSetRoundToNearest;

    protected bool $numeric = false;

    public function numeric(int|Closure|null $decimalPlaces = null, int|Closure|null $roundToNearest = null, string|Closure|null $locale = null, int|Closure|null $divideBy = null): static
    {
        $this->numeric = true;
        $this->setDecimalPlaces($decimalPlaces);
        $this->setRoundToNearest($roundToNearest);
        $this->setDivideBy($divideBy);
        $this->setLocale($locale);

        return $this;
    }

    public function formatsNumeric(): bool
    {
        return $this->numeric;
    }

    public function formatNumeric(mixed $value): mixed
    {
        if (! is_numeric($value)) {
            return $value;
        }

        if ($this->hasDivideBy()) {
            $value /= $this->getDivideBy();
        }

        if ($this->hasRoundToNearest()) {
            $value = round($value / $this->getRoundToNearest()) * $this->getRoundToNearest();
        }

        if ($this->hasDecimalPlaces() || $this->hasLocale()) {
            return Number::format($value, precision: $this->getDecimalPlaces(), locale: $this->getLocale());
        }

        return (string) $value;
    }
}
