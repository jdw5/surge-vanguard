<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

use Closure;

trait HasConfirmationType
{
    protected string|Closure|null $confirmationType = null;

    public function confirmationType(ConfirmationType|string|Closure $confirmationType): static
    {
        $this->setConfirmationType($confirmationType);

        return $this;
    }

    public function setConfirmationType(ConfirmationType|string|Closure|null $confirmationType): void
    {
        if (is_null($confirmationType)) {
            return;
        }
        $this->confirmationType = $confirmationType instanceof ConfirmationType ? $confirmationType->value : $confirmationType;
    }

    public function hasConfirmationType(): bool
    {
        return ! $this->lacksConfirmationType();
    }

    public function lacksConfirmationType(): bool
    {
        return is_null($this->confirmationType);
    }

    public function getConfirmationType(): ?string
    {
        return $this->evaluate($this->confirmationType);
    }

    public function constructive(): static
    {
        return $this->confirmationType(ConfirmationType::Constructive);
    }

    public function destructive(): static
    {
        return $this->confirmationType(ConfirmationType::Destructive);
    }

    public function informative(): static
    {
        return $this->confirmationType(ConfirmationType::Informative);
    }
}
