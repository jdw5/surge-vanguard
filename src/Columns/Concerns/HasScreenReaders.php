<?php

namespace Jdw5\Vanguard\Columns\Concerns;

trait HasScreenReaders
{
    protected bool $srOnly = false;

    protected function setScreenReader(bool $srOnly): void
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
        $this->setScreenReader(true);
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
        $this->setScreenReader(false);
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

    public function isScreenReader(): bool
    {
        return $this->srOnly;
    }

    public function isSrOnly(): bool
    {
        return $this->srOnly;
    }



}

