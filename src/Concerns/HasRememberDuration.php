<?php

namespace Conquest\Table\Concerns;

trait HasRememberDuration
{
    protected int $rememberFor = 30*24*60*60; // seconds

    public static function setGlobalRememberFor(int $seconds): void
    {
        static::$rememberFor = $seconds;
    }

    public function rememberFor(int $seconds): static
    {
        $this->setRememberFor($seconds);
        return $this;
    }

    protected function setRememberFor(int|null $seconds): void
    {
        if (is_null($seconds)) return;
        $this->rememberFor = $seconds;
    }

    public function getRememberFor(): int
    {
        return $this->rememberFor;
    }
}