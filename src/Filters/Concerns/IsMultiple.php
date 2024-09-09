<?php

declare(strict_types=1);

namespace Conquest\Table\Filters\Concerns;

use Closure;

trait IsMultiple
{
    protected bool|Closure $multiple = false;

    public function multiple(bool|Closure $multiple = true): static
    {
        $this->setMultiple($multiple);

        return $this;
    }

    public function setMultiple(bool|Closure|null $multiple): void
    {
        if (is_null($multiple)) {
            return;
        }
        $this->multiple = $multiple;
    }

    public function isMultiple(): bool
    {
        return (bool) $this->evaluate($this->multiple);
    }

    public function isNotMultiple(): bool
    {
        return ! $this->isMultiple();
    }

    public function toMultiple(?string $value): array
    {
        if (is_null($value)) {
            return [];
        }

        return array_map('trim', explode(',', $value));
    }
}
