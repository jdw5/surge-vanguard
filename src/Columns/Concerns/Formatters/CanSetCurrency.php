<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait CanSetCurrency
{
    protected string|Closure|null $currency = null;

    protected function setCurrency(string|Closure|null $currency): void
    {
        if (is_null($currency)) return;
        $this->currency = $currency;
    }

    protected function hasCurrency(): bool
    {
        return !$this->lacksCurrency();
    }

    protected function lacksCurrency(): bool
    {
        return is_null($this->currency);
    }

    protected function getCurrency(): ?string
    {
        return $this->evaluate($this->currency);
    }
}