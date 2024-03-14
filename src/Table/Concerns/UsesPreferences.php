<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait UsesPreferences
{
    protected $preferences;

    public function hasPreferences(): bool
    {
        return isset($this->preferences);
    }

    // Needs to set a cookie with the preferences if one doesn't exist
    // If one doesn't exist, we use the default preferences
    // Need to reduce the columns based on the preferences
}