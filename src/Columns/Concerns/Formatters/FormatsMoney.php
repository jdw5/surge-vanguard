<?php

declare(strict_types=1);

namespace Conquest\Table\Concerns\Formatters;

use Closure;
use Conquest\Table\Columns\Concerns\Formatters\CanSetCurrency;
use Conquest\Table\Columns\Concerns\Formatters\CanSetDivideBy;
use Conquest\Table\Columns\Concerns\Formatters\CanSetVerbose;

trait FormatsMoney
{
    use CanSetDivideBy;
    use CanSetVerbose;
    use CanSetCurrency;

    protected bool $money = false;

    public function money(string|Closure $currency = null, int|Closure $divideBy = null, bool|Closure $verbose = false): static
    {
        $this->setDivideBy($divideBy);
        $this->setCurrency($currency);
        $this->setVerbose($verbose);
        $this->money = true;
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
