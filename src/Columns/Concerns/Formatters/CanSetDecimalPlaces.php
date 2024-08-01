<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait CanSetDecimalPlaces
{
    protected int|Closure|null $decimalPlaces = null;

    protected function setDecimalPlaces(int|Closure|null $decimalPlaces): void
    {
        if (is_null($decimalPlaces)) {
            return;
        }
        $this->decimalPlaces = $decimalPlaces;
    }

    public function hasDecimalPlaces(): bool
    {
        return ! $this->lacksDecimalPlaces();
    }

    public function lacksDecimalPlaces(): bool
    {
        return is_null($this->decimalPlaces);
    }

    public function getDecimalPlaces(): ?int
    {
        return $this->evaluate($this->decimalPlaces);
    }
}
