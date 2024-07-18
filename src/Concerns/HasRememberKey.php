<?php

namespace Conquest\Table\Concerns;

/**
 * If defined, preferences will be remembered
 */
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

    public function setRememberKey(string|null $key): void
    {
        if (is_null($key)) return;
        $this->rememberAs = $key;
    }

    public function lacksRememberKey(): bool
    {
        return is_null($this->getRememberKey());
    }

    public function hasRememberKey(): bool
    {
        return ! $this->lacksRememberKey();
    }
}
