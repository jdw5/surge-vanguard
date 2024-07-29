<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;

trait IsBulk
{
    protected bool|Closure $bulk = false;

    public function bulk(bool|Closure $bulk = true): static
    {
        $this->setBulk($bulk);
        return $this;
    }

    public function setBulk(bool|Closure|null $bulk): void
    {
        if (is_null($bulk)) return;
        $this->bulk = $bulk;
    }

    public function isBulk(): bool
    {
        return $this->evaluate($this->bulk);
    }

    public function isNotBulk(): bool
    {
        return !$this->isBulk();
    }
}