<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

trait HasConfirmationType
{
    protected ?ConfirmationType $confirmationType = null;

    public function confirmationType(ConfirmationType|string $confirmationType): static
    {
        $this->setConfirmationType($confirmationType);

        return $this;
    }

    public function setConfirmationType(ConfirmationType|string|null $confirmationType): void
    {
        if (is_null($confirmationType)) {
            return;
        }
        $this->confirmationType = $confirmationType instanceof ConfirmationType ? $confirmationType : ConfirmationType::tryFrom($confirmationType);
    }

    public function hasConfirmationType(): bool
    {
        return ! $this->lacksConfirmationType();
    }

    public function lacksConfirmationType(): bool
    {
        return is_null($this->confirmationType);
    }

    public function getConfirmationType(): ?ConfirmationType
    {
        return $this->confirmationType;
    }

    public function getConfirmationTypeValue(): ?string
    {
        return $this->getConfirmationType()?->value;
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
