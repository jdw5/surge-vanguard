<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;
use Conquest\Table\Actions\Concerns\Confirmation\ConfirmationType;

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
        if (is_null($confirmationType)) return;

        if ($confirmationType instanceof ConfirmationType) {
            $this->confirmationType = $confirmationType;
        } else {
            $this->confirmationType = ConfirmationType::tryFrom($confirmationType);
        }
    }

    public function hasConfirmationType(): bool
    {
        return !$this->lacksConfirmationType();
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