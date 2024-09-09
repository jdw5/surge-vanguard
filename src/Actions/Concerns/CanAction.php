<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;

trait CanAction
{
    protected ?Closure $action = null;

    public function action(Closure|string $action): static
    {
        $this->setAction($action);

        return $this;
    }

    /**
     * Alias for action
     */
    public function each(Closure|string $action): static
    {
        return $this->action($action);
    }

    public function cannotAction(): bool
    {
        return is_null($this->action);
    }

    public function canAction(): bool
    {
        return ! $this->cannotAction();
    }

    public function setAction(Closure|string|null $action): void
    {
        if (is_null($action)) {
            return;
        }

        if (\is_string($action) && class_exists($action) && method_exists($action, '__invoke')) {
            $action = resolve($action)->__invoke(...);
        }

        $this->action = $action;
    }

    public function getAction(): ?Closure
    {
        return $this->action;
    }

    public function applyAction(string $modelClass, mixed $record): void
    {
        $this->evaluate(
            value: $this->getAction(),
            named: [
                'record' => $record,
            ],
            typed: [
                Model::class => $record,
                $modelClass => $record,
            ],
        );
    }
}
