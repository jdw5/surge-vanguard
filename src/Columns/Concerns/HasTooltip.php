<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait HasTooltip
{
    protected string|Closure|null $tooltip = null;

    public function tooltip(string|Closure $tooltip): static
    {
        $this->setTooltip($tooltip);

        return $this;
    }

    public function setTooltip(string|Closure|null $tooltip): void
    {
        if (is_null($tooltip)) {
            return;
        }
        $this->tooltip = $tooltip;
    }

    public function hasTooltip(): bool
    {
        return ! $this->lacksTooltip();
    }

    public function lacksTooltip(): bool
    {
        return is_null($this->tooltip);
    }

    public function getTooltip(): ?string
    {
        return $this->evaluate(
            value: $this->tooltip,
        );
    }
}
