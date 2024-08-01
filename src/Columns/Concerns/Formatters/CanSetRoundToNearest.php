<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait CanSetRoundToNearest
{
    protected int|Closure|null $roundToNearest = null;

    protected function setRoundToNearest(int|Closure|null $roundToNearest): void
    {
        if (is_null($roundToNearest)) {
            return;
        }
        $this->roundToNearest = $roundToNearest;
    }

    protected function hasRoundToNearest(): bool
    {
        return ! $this->lacksRoundToNearest();
    }

    protected function lacksRoundToNearest(): bool
    {
        return is_null($this->roundToNearest);
    }

    protected function getRoundToNearest(): ?int
    {
        return $this->evaluate($this->roundToNearest);
    }
}
