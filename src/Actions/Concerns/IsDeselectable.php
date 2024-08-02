<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;

trait IsDeselectable
{
    protected bool|Closure $deselectable = true;

    public function deselectable(bool|Closure $deselectable = false): static
    {
        $this->setDeselectable($deselectable);

        return $this;
    }

    /**
     * Alias for deselectable.
     */
    public function deselect(bool|Closure $deselectable = false): static
    {
        return $this->deselectable($deselectable);
    }

    public function setDeselectable(bool|Closure|null $deselectable): void
    {
        if (is_null($deselectable)) {
            return;
        }

        $this->deselectable = $deselectable;
    }

    public function isDeselectable(): bool
    {
        return (bool) $this->evaluate($this->deselectable);
    }

    public function isNotDeselectable(): bool
    {
        return ! $this->isDeselectable();
    }
}
