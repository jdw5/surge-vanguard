<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait FormatsSeparator
{
    protected string|Closure|null $separator = null;

    public function separator(string|Closure $separator = ', '): static
    {
        $this->separator = $separator;

        return $this;
    }

    public function formatsSeparator(): bool
    {
        return ! is_null($this->separator);
    }

    public function getSeparator(): ?string
    {
        return $this->evaluate($this->separator);
    }

    public function formatSeparator(mixed $value): string
    {
        if (! is_array($value)) {
            return $value;
        }

        return implode($this->getSeparator(), $value);
    }
}
