<?php

namespace Jdw5\Vanguard\Actions\Concerns;

use Closure;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Actions\ActionType;
use ReflectionFunction;

trait HasHandler
{
    protected Closure $handle;
    protected string|Closure $redirect;

    protected function setHandler(Closure $handle): void
    {
        $this->handle = $handle;
    }

    protected function setRedirect(string|Closure $redirect): void
    {
        if (is_null($redirect)) return;
        $this->redirect = $redirect;
    }

    public function handle(Closure $handle): static
    {
        $this->setHandler($handle);
        return $this;
    }

    public function redirect(string|Closure $redirect): static
    {
        $this->setRedirect($redirect);
        return $this;
    }

    public function hasHandler(): bool
    {
        return isset($this->handle);
    }

    public function hasRedirect(): bool
    {
        return isset($this->redirect);
    }

    public function getHandler(): Closure
    {
        return $this->handle;
    }

    public function getRedirect(): string|Closure
    {
        return $this->evaluate($this->redirect);
    }

    public function usesRecord(): bool
    {
        return $this->hasHandler() && (new ReflectionFunction($this->handle))->getNumberOfParameters() > 0;
    }
}