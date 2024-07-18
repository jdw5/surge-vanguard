<?php

namespace Conquest\Table\Concerns\Remember;

trait HasCookie
{
    protected $cookie;

    public function hasCookie(): bool
    {
        if (isset($this->cookie)) {
            return $this->cookie;
        }

        return config('table.remember.cookie', false);
    }

    public function setCookie(bool|null $cookie): void
    {
        if (is_null($cookie)) return;
        $this->cookie = $cookie;
    }
}