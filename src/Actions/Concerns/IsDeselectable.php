<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;

trait IsDeselectable
{
    protected bool|Closure $deselect = true;

    public function deselect(bool|Closure $deselect = true): static
    {
        $this->setDeselect($deselect);

        return $this;
    }

    public function setDeselect(bool|Closure|null $deselect): void
    {
        if (is_null($deselect)) {
            return;
        }

        $this->deselect = $deselect;
    }

    public function isDeselectable(): bool
    {
        return (bool) $this->evaluate($this->deselect);
    }

    public function isNotDeselectable(): bool
    {
        return (bool) $this->evaluate($this->deselect);
    }
}
