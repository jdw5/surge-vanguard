<?php

namespace Conquest\Table\Concerns\Remember;

trait HasToggleKey
{
    protected $toggleKey;

    public function getToggleKey(): ?string
    {
        if (isset($this->toggleKey)) {
            return $this->toggleKey;
        }

        return config('table.remember.toggle_key', 'cols');
    }

    public function setToggleKey(string|null $key): void
    {
        if (is_null($key)) return;
        $this->toggleKey = $key;
    }

}
