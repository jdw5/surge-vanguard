<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait UsesPreferences
{
    protected $preferences;
    protected $cookieName;

    public function hasPreferences(): bool
    {
        return isset($this->preferences);
    }

    // Needs to set a cookie with the preferences if one doesn't exist
    // If one doesn't exist, we use the default preferences
    // Need to reduce the columns based on the preferences

    public function cookieName()
    {
        if (!isset($this->cookieName)) {
            // $this->cookieName = 
        }

        return str(static::class)
            ->classBasename();
    }


}