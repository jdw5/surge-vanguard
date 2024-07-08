<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Conquest\Table\DataObjects\ActionTypeData;
use Conquest\Table\DataObjects\InlineActionData;
use Conquest\Table\Actions\DataTransferObjects\BulkActionData;

trait HasAction
{

    protected ?Closure $action = null;

    public function action(Closure|string|null $action): static
    {
        $this->setAction($action);
        return $this;
    }

    public function hasAction(): bool
    {
        return !is_null($this->action);
    }

    public function setAction(Closure|string|null $action): void
    {
        // Allow for invokable actions
        if (\is_string($action) && class_exists($action) && method_exists($action, '__invoke')) {
            $action = resolve($action)->__invoke(...);
        }
        $this->action = $action;
    }

    public function getAction(): ?Closure
    {
        return $this->action;
    }

    
}