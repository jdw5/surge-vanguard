<?php

namespace Jdw5\Vanguard\Columns\Concerns;

use Closure;

trait HasValidator
{
    protected Closure $validator;

    public function validate(Closure $callback): static
    {
        $this->setValidator($callback);
        return $this;
    }

    public function validateUsing(Closure $callback): static
    {
        return $this->validate($callback);
    }

    protected function setValidator(Closure $callback): void
    {
        $this->validator = $callback;
    }

    public function hasValidator(): bool
    {
        return isset($this->validator);
    }
}