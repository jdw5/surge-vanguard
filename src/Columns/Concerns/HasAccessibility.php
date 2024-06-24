<?php

namespace Jdw5\Vanguard\Columns\Concerns;

trait HasAccessibility
{
    protected bool $srOnly = false;
    
    protected function setSrOnly(bool $srOnly): void
    {
        $this->srOnly = $srOnly;
    }

    /**
     * Set the column to be screen reader only
     * 
     * @return static
     */
    public function srOnly(): static
    {
        $this->setSrOnly(true);
        return $this;
    }

    public function screenReader(): static
    {
        return $this->srOnly();
    }

    public function forAccessibility(): static
    {
        return $this->srOnly();
    }

    public function notSrOnly(): static
    {
        $this->setSrOnly(false);
        return $this;
    }

    public function notScreenReader(): static
    {
        return $this->notSrOnly();
    }

    public function notForAccessibility(): static
    {
        return $this->notSrOnly();
    }

    public function isForAccessibility(): bool
    {
        return $this->srOnly;
    }

    public function isScreenReaderOnly(): bool
    {
        return $this->srOnly;
    }

    public function isSrOnly(): bool
    {
        return $this->srOnly;
    }



}
