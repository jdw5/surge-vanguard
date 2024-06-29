<?php

namespace Conquest\Table\Concerns;

trait HasRememberKey
{
    protected string $rememberAs;

    public function getRememberKey(): ?string
    {
        if (isset($this->rememberAs)) {
            return $this->rememberAs;
        }

        if (method_exists($this, 'rememberAs')) {
            return $this->rememberAs();
        }

        return null;
    }

    public function hasRememberKey(): bool
    {
        return !is_null($this->getRememberKey());
    }
}