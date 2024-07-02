<?php

namespace Conquest\Table\Concerns;

trait HasRememberDuration
{
    protected int $rememberFor = 30*24*60*60; // seconds

    /**
     * The global remember duration (seconds).
     */
    public static function setGlobalRememberFor(int $seconds): void
    {
        static::$rememberFor = $seconds;
    }

    /**
     * Set the remember duration (seconds), chainable.
     * 
     * @param int $seconds
     * @return static
     */
    public function rememberFor(int $seconds): static
    {
        $this->setRememberFor($seconds);
        return $this;
    }

    /**
     * Set the remember duration (seconds) quietly.
     * 
     * @param int|null $seconds
     * @return void
     */
    protected function setRememberFor(int|null $seconds): void
    {
        if (is_null($seconds)) return;
        $this->rememberFor = $seconds;
    }

    /**
     * Retrieve the remember duration (seconds)
     * 
     * @return int
     */
    public function getRememberFor(): int
    {
        return $this->rememberFor;
    }

}