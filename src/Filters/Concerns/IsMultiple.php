<?php

namespace Conquest\Table\Filters\Concerns;

trait IsMultiple
{
    protected bool $multiple = false;

    public function multiple(): static
    {
        $this->setMultiple(true);
        return $this;
    }

    public function notMultiple(): static
    {
        $this->setMultiple(false);
        return $this;
    }

    public function setMultiple(bool|null $multiple): void
    {
        if (is_null($multiple)) return;
        $this->multiple = $multiple;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function splitToMultiple(?string $value): array
    {
        return array_map('trim', explode(',', $value));
    }
}
