<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

use Closure;

trait HasConfirmationMessage
{
    protected string|Closure|null $confirmationMessage = null;

    public function confirmationMessage(string|Closure $confirmationMessage): static
    {
        $this->setConfirmationMessage($confirmationMessage);

        return $this;
    }

    public function setConfirmationMessage(string|Closure|null $confirmationMessage): void
    {
        if (is_null($confirmationMessage)) {
            return;
        }
        $this->confirmationMessage = $confirmationMessage;
    }

    public function hasConfirmationMessage(): bool
    {
        return ! $this->lacksConfirmationMessage();
    }

    public function lacksConfirmationMessage(): bool
    {
        return is_null($this->confirmationMessage);
    }

    public function getConfirmationMessage(): ?string
    {
        return $this->evaluate(
            value: $this->confirmationMessage,
        );
    }
}
