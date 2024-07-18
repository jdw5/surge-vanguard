<?php

namespace Conquest\Table\Concerns\Remember;

trait HasRememberFor
{
    protected $rememberDuration;

    /**
     * Retrieve the remember duration (seconds)
     * 
     * @return int
     */
    public function getRememberFor(): int
    {
        if ($this->lacksRememberDuration()) {
            return config('table.remember.duration', 30*24*60*60);
        }
        return $this->rememberDuration;
    }
    
    /**
     * Set the remember duration (seconds) quietly.
     * 
     * @param int|null $seconds
     * @return void
     */
    public function setRememberDuration(int|null $seconds): void
    {
        if (is_null($seconds)) return;
        $this->rememberDuration = $seconds;
    }
}
