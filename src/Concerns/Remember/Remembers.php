<?php

namespace Conquest\Table\Concerns\Remember;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

trait Remembers
{
    use HasCookie;
    use HasRememberFor;
    use HasToggleKey;
    use HasCookieName;

    protected $remember;

    public function remember(string $cookieKey = null, int $duration = null, string $toggleKey = null, bool $cookie = null): static
    {
        $this->setRemember(true);
        $this->setCookieName($cookieKey);
        $this->setRememberFor($duration);
        $this->setCookie($cookie);
        $this->setToggleKey($toggleKey);
        return $this;
    }

    public function remembers(): bool
    {
        if (isset($this->remember)) {
            return $this->remember;
        }

        return config('table.remember.default', false);
    }

    public function setRemember(bool|null $remember): void
    {
        if (is_null($remember)) return;
        $this->remember = $remember;
    }

    public function getRememberedFromRequest(): array
    {
        $data = Request::input($this->getToggleKey());
        return explode(',', $data);
    }

    public function encodeCookie(mixed $data): void
    {
        Cookie::queue($this->getCookieName(), json_encode($data), $this->getRememberFor());
    }

    public function decodeCookie(): mixed
    {
        return json_decode(Request::cookie($this->getCookieName()), true);
    }
}
