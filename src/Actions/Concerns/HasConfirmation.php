<?php

namespace Conquest\Table\Actions\Concerns;

use Closure;

trait HasConfirmation
{
    protected bool|Closure $confirm = false;

    protected string|Closure $confirmMessage = 'Are you sure you want to do this?';

    protected function setConfirm(bool $confirm): void
    {
        $this->confirm = $confirm;
    }

    public function confirm(bool $confirm): static
    {
        $this->setConfirm($confirm);

        return $this;
    }

    public function requiresConfirm(): bool
    {
        return $this->evaluate($this->confirm);
    }

    protected function setConfirmMessage(string $message): void
    {
        $this->confirmMessage = $message;
    }

    public function getConfirmMessage(): string
    {
        return $this->evaluate($this->confirmMessage);
    }
}
