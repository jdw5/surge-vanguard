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

    public function hasRoundToNearest(): bool
    {
        return ! $this->lacksRoundToNearest();
    }

    public function lacksRoundToNearest(): bool
    {
        return is_null($this->roundToNearest);
    }

    public function getRoundToNearest(): ?int
    {
        return $this->evaluate($this->roundToNearest);
    }
}
