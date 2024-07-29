<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait HasSeparator
{
    protected string|Closure|null $separator = null;

    public function separator(string|Closure $separator): static
    {
        $this->setSeparator($separator);
        return $this;
    }

    public function setSeparator(string|Closure|null $separator): void
    {
        if (is_null($separator)) return;
        $this->separator = $separator;
    }

    public function hasSeparator(): bool
    {
        return !$this->lacksSeparator();
    }

    public function lacksSeparator(): bool
    {
        return is_null($this->separator);
    }

    public function getSeparator(): ?string
    {
        return $this->evaluate(
            value: $this->separator,
        );
    }
}