<?php

namespace Jdw5\Vanguard\Filters\Concerns;

use Closure;

trait HasValidator
{
    protected Closure $validator = null;

    public function validate(Closure $callback): static
    {
        $this->setValidator($callback);
        return $this;
    }

    public function validator(Closure $callback): static
    {
        return $this->validate($callback);
    }

    /** If nothing is returned, validation has failed */
    public function validateUsing(mixed $value): mixed
    {
        if (!$this->hasValidator()) return $value;
        return $this->peformValidation($value);
    }

    protected function setValidator(Closure $callback): void
    {
        $this->validator = $callback;
    }

    public function hasValidator(): bool
    {
        return !is_null($this->validator);
    }

    protected function peformValidation(mixed $value): mixed
    {
        return ($this->validator)($value);
    }
}