<?php

namespace Conquest\Table\Concerns\Remember;

trait RemembersAsKey
{
    protected $rememberAs;

    public function getRememberKey(): ?string
    {
        if (isset($this->rememberAs)) {
            return $this->rememberAs;
        }

        return str(class_basename($this))->snake();
    }

    public function setRememberKey(string|null $key): void
    {
        if (is_null($key)) return;
        $this->rememberAs = $key;
    }
}
