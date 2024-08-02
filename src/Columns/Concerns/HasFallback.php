<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

trait HasFallback
{
    protected mixed $fallback = null;

    public function fallback(mixed $fallback): static
    {
        $this->setFallback($fallback);

        return $this;
    }

    public function setFallback(mixed $fallback): void
    {
        $this->fallback = $fallback;
    }

    public function hasFallback(): bool
    {
        return ! $this->lacksFallback();
    }

    public function lacksFallback(): bool
    {
        return is_null($this->getFallback());
    }

    public function getFallback(): mixed
    {
        return $this->evaluate($this->fallback);
    }
}
