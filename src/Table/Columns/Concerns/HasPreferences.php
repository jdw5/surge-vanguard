<?php

namespace Jdw5\Vanguard\Table\Concerns\Columns;

use Jdw5\Vanguard\Table\Exceptions\KeyCannotBePreference;

trait HasPreferences
{
    protected bool $enabled = false;
    protected bool $isPreference = false;
    protected bool $default = false;
    
    public function preference(bool $default = false): static
    {
        if ($this->isKey()) {
            throw KeyCannotBePreference::invalid();
        }

        $this->enabled = true;
        $this->default = $default;
        return $this;
    }

    public function hasPreference(): bool
    {
        return $this->enabled;
    }

    public function isPreference(): bool
    {
        return $this->isPreference;
    }

    public function getDefault(): bool
    {
        return $this->default;
    }
}