<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait FormatsMoney
{
    use CanSetDivideBy;
    use CanSetVerbose;
    use CanSetCurrency;
    use CanSetLocale;

    protected bool $money = false;

    public function money(string|Closure $currency = null, int|Closure $divideBy = null, string|Closure $locale = null, bool|Closure $verbose = false): static
    {
        $this->money = true;
        $this->setCurrency($currency);
        $this->setDivideBy($divideBy);
        $this->setLocale($locale);
        $this->setVerbose($verbose);
        return $this;
    }
    
    public function isMoney(): bool
    {
        return $this->money;
    }

    public function isNotMoney(): bool
    {
        return  ! $this->isMoney();
    }

    public function formatMoney(mixed $value)
    {
        if ($this->isMoney()) {
            return number_format($value);
        }

        return $value;
    }
}
