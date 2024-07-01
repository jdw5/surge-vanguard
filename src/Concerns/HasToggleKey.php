<?php

namespace Conquest\Table\Concerns;

use Closure;

trait HasToggleKey
{
    protected string|Closure $toggleKey = 'cols';

    public static function setGlobalToggleKey(string|Closure $key): void
    {
        static::$toggleKey = $key;
    }

    public function toggleKey(string|Closure $key): static
    {
        $this->setToggleKey($key);

        return $this;
    }

    protected function setToggleKey(string|Closure|null $key): void
    {
        if (is_null($key)) {
            return;
        }
        $this->toggleKey = $key;
    }

    public function getToggleKey(): string
    {
        return $this->evaluate($this->toggleKey);
    }

    public function getActiveToggles(): array
    {
        return explode(',', request()->query($this->getToggleKey(), ''));
    }
}
