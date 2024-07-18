<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;

trait HasConfirmation
{
    protected string|Closure|null $confirmation = null;

    public function confirmation(string|Closure $confirm = 'Are you sure want to do this?'): static
    {
        $this->setConfirm($confirm);
        return $this;
    }

    protected function setConfirmation(string|Closure|null $message): void
    {
        $this->confirmation = $message;
    }

    public function getConfirmation(): string
    {
        return $this->evaluate(
            value: $this->confirmation,
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
