<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;
use Illuminate\Http\Request;
use Conquest\Table\Actions\ActionType;
use ReflectionFunction;

trait HasHandler
{
    protected Closure|null $handle = null;
    protected Closure|null $after = null;

    public function setHandler(Closure $handle): void
    {
        $this->handle = $handle;
    }

    public function handle(Closure $handle): static
    {
        $this->setHandler($handle);
        return $this;
    }

    public function hasHandler(): bool
    {
        return !is_null($this->handle);
    }

    public function getHandler(): Closure
    {
        return $this->handle;
    }

    public function usesRecord(): bool
    {
        return $this->hasHandler() && (new ReflectionFunction($this->handle))->getNumberOfParameters() > 0;
    }

    public function after(Closure $after): static
    {
        $this->setAfter($after);
        return $this;
    }

    public function setAfter(Closure|null $after): void
    {
        if (is_null($after)) return;
        $this->after = $after;
    }

    public function hasAfter(): bool
    {
        return !is_null($this->after);
    }

    public function onAfter()
    {
        if (!$this->hasAfter()) return;
        return ($this->handle)();
    }

    public function apply(mixed $record)
    {
        if (!$this->hasHandler()) return;
        ($this->handle)($record);
        return $this->onAfter();
    }
}