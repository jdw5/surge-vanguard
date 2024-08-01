<?php

namespace Conquest\Table\Concerns\Remember;

trait HasCookieName
{
    protected $cookieName;

    public function getCookieName(): ?string
    {
        if (isset($this->cookieName)) {
            return $this->cookieName;
        }

        return str(class_basename($this))->snake();
    }

    public function setCookieName(?string $key): void
    {
        if (is_null($key)) {
            return;
        }
        $this->cookieName = $key;
    }
}
