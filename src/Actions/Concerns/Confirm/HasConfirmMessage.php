<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirm;

use Closure;

trait HasConfirmMessage
{
    protected string|Closure|null $confirmMessage = null;

    public function confirmMessage(string|Closure $confirmMessage): static
    {
        $this->setConfirmMessage($confirmMessage);

        return $this;
    }

    public function setConfirmMessage(string|Closure|null $confirmMessage): void
    {
        if (is_null($confirmMessage)) {
            return;
        }
        $this->confirmMessage = $confirmMessage;
    }

    public function hasConfirmMessage(): bool
    {
        return ! $this->lacksConfirmMessage();
    }

    public function lacksConfirmMessage(): bool
    {
        return is_null($this->confirmMessage);
    }

    public function getConfirmMessage(): string
    {
        return $this->evaluate($this->confirmMessage) ?? config('table.confirm.message', 'Are you sure you want to perform this action?');
    }
}
