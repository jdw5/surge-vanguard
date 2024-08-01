<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait HasTruthLabel
{
    protected string|Closure|null $truthLabel = null;

    public function truthLabel(string|Closure $truthLabel): static
    {
        $this->setTruthLabel($truthLabel);

        return $this;
    }

    public function setTruthLabel(string|Closure|null $truthLabel): void
    {
        if (is_null($truthLabel)) {
            return;
        }
        $this->truthLabel = $truthLabel;
    }

    public function getTruthLabel(): string
    {
        return $this->evaluate($this->truthLabel) ?? config('table.fallback.true', 'Yes');
    }
}
