<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirm;

use Closure;

trait HasConfirmType
{
    protected string|Closure|null $confirmType = null;

    public function confirmType(ConfirmType|string|Closure $confirmType): static
    {
        $this->setConfirmType($confirmType);

        return $this;
    }

    public function setConfirmType(ConfirmType|string|Closure|null $confirmType): void
    {
        if (is_null($confirmType)) {
            return;
        }
        $this->confirmType = $confirmType instanceof ConfirmType ? $confirmType->value : $confirmType;
    }

    public function hasConfirmType(): bool
    {
        return ! $this->lacksConfirmType();
    }

    public function lacksConfirmType(): bool
    {
        return is_null($this->confirmType);
    }

    public function getConfirmType(): ?string
    {
        return $this->evaluate($this->confirmType);
    }

    public function constructive(): static
    {
        return $this->confirmType(ConfirmType::Constructive);
    }

    public function destructive(): static
    {
        return $this->confirmType(ConfirmType::Destructive);
    }

    public function informative(): static
    {
        return $this->confirmType(ConfirmType::Informative);
    }
}
