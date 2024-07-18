<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;
use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\InlineAction;
use Illuminate\Database\Eloquent\Model;

trait HasConfirmation
{
    protected string|Closure|null $confirmation = null;

    public function confirmation(string|Closure $confirm = 'Are you sure want to do this?'): static
    {
        $this->setConfirmation($confirm);
        return $this;
    }

    protected function setConfirmation(string|Closure|null $message): void
    {
        $this->confirmation = $message;
    }

    public function resolveConfirmation(mixed $record = null, string $modelClass = Model::class): void
    {
        $this->setConfirmation(
            $this->evaluate(
                value: $this->confirmation,
                named: [
                    'action' => $this,
                    'record' => $record,
                    'name' => $this->getName(),
                    'label' => $this->getLabel(),
                ],
                typed: [
                    Model::class => $record,
                    $modelClass => $record,
                ],
            ),
        );
    }

    public function getConfirmation(): ?string
    {
        return $this->evaluate(
            value: $this->confirmation,
            named: [
                'action' => $this,
                'label' => $this->getLabel(),
                'name' => $this->getName(),
            ],

        );
    }

    public function hasConfirmation(): bool
    {
        return !$this->hasConfirmation();
    }

    public function lacksConfirmation(): bool
    {
        return is_null($this->confirmation);
    }

}
