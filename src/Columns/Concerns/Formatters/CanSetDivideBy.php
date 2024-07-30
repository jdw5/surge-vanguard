<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait CanSetDivideBy
{
    protected int|Closure|null $divideBy = null;

    protected function setDivideBy(int|Closure|null $divideBy): void
    {
        if (is_null($divideBy)) return;
        $this->divideBy = $divideBy;
    }

    protected function hasDivideBy(): bool
    {
        return !$this->lacksDivideBy();
    }

    protected function lacksDivideBy(): bool
    {
        return is_null($this->divideBy);
    }

    protected function getDivideBy(): int
    {
        return $this->evaluate($this->divideBy);
    }
}