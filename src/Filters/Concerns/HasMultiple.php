<?php

namespace Jdw5\Vanguard\Filters\Concerns;

trait HasMultiple
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

    protected function setMultiple(bool|null $multiple): void
    {
        if (is_null($multiple)) return;
        $this->multiple = $multiple;
    }

    public function getMultiple(): bool
    {
        return $this->evaluate($this->multiple);
    }

    public function isMultiple(): bool
    {
        return $this->getMultiple();
    }

    public function hasMultiple(): bool
    {
        return $this->getMultiple();
    }

    public function splitToMultiple(string|null $value): array
    {
        return array_map('trim', explode(',', $value));
    }
}