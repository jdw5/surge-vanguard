<?php

namespace Conquest\Table\Concerns\Remember;

trait HasRememberFor
{
    protected $rememberFor;

    /**
     * Retrieve the remember duration (seconds)
     * 
     * @return int
     */
    public function getRememberFor(): int
    {
        if (isset($this->rememberFor)) {
            return $this->rememberFor;
        }
        
        return config('table.remember.duration', 30*24*60*60);
    }

    /**
     * Set the remember duration (seconds) quietly.
     * 
     * @param int|null $seconds
     * @return void
     */
    public function setRememberFor(int|null $seconds): void
    {
        if (is_null($seconds)) return;
        $this->rememberFor = $seconds;
    }
}
