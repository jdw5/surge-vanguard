<?php

namespace Conquest\Table\Concerns\Remember;

trait Remembers
{
    protected $remember;

    public function remembers(): bool
    {
        if (isset($this->remember)) {
            return $this->remember;
        }

        if (method_exists($this, 'remember')) {
            return $this->remember();
        }

        return config('table.remember.default', false);
    }

    public function setRemember(bool|null $remember): void
    {
        if (is_null($remember)) return;
        $this->remember = $remember;
    }
}
