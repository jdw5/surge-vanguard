<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait HasPrefix
{
    protected string|Closure|null $prefix = null;

    public function prefix(string|Closure $prefix): static
    {
        $this->setPrefix($prefix);
        return $this;
    }

    public function setPrefix(string|Closure|null $prefix): void
    {
        if (is_null($prefix)) return;
        $this->prefix = $prefix;
    }

    public function hasPrefix(): bool
    {
        return !$this->lacksPrefix();
    }

    public function lacksPrefix(): bool
    {
        return is_null($this->prefix);
    }

    public function getPrefix(): ?string
    {
        return $this->evaluate(
            value: $this->prefix,
        );
    }
}