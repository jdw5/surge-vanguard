<?php
declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

use Closure;

trait HasConfirmationTitle
{
    protected string|Closure|null $confirmationTitle = null;

    public function confirmationTitle(string|Closure $confirmationTitle): static
    {
        $this->setConfirmationTitle($confirmationTitle);
        return $this;
    }

    public function setConfirmationTitle(string|Closure|null $confirmationTitle): void
    {
        if (is_null($confirmationTitle)) return;
        $this->confirmationTitle = $confirmationTitle;
    }

    public function hasConfirmationTitle(): bool
    {
        return !$this->lacksConfirmationTitle();
    }

    public function lacksConfirmationTitle(): bool
    {
        return is_null($this->confirmationTitle);
    }

    public function getConfirmationTitle(): ?string
    {
        return $this->evaluate($this->confirmationTitle);
    }

    // public function resolveConfirmationTitle(mixed $model): void
    // {
    //     $this->confirmationTitle = $this->evaluate(
    //         value: $this->confirmationTitle,
    //         model: $model,
    //     );
    // }
}