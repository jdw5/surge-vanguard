<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;
use Illuminate\Support\Number;

trait FormatsMoney
{
    use CanSetCurrency;
    use CanSetDivideBy;
    use CanSetLocale;

    protected bool $money = false;

    public function money(string|Closure|null $currency = null, int|Closure|null $divideBy = null, string|Closure|null $locale = null): static
    {
        $this->money = true;
        $this->setCurrency($currency);
        $this->setDivideBy($divideBy);
        $this->setLocale($locale);

        return $this;
    }

    public function formatsMoney(): bool
    {
        return $this->money;
    }

    public function formatMoney(mixed $value): mixed
    {
        if (! is_numeric($value)) {
            return $value;
        }

        if ($this->hasDivideBy()) {
            $value /= $this->getDivideBy();
        }

        return Number::currency($value, $this->getCurrency() ?? config('app.currency', 'USD'), $this->getLocale());
    }
}
