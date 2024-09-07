<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns;

use Closure;
use ReflectionClass;
use Conquest\Table\Actions\Confirm\Confirm as Confirmable;

trait CanBeConfirmable
{
    protected Confirmable|null $confirm = null;

    /**
     * Set the properties of the confirmations
     */
    public function confirm(Closure|array|false $confirm): static
    {
        $this->setConfirm(!!$confirm);

        if (! $this->isConfirmable()) {
            return $this;
        }
        
        if (is_array($confirm)) {
            $this->confirm->setState($confirm);
        } 
        
        if (is_callable($confirm)) {
            $confirm($this->confirm);
        }

        return $this;
    }

    public function setConfirm(Confirmable|bool|null $confirm): void
    {
        if (is_null($confirm)) {
            return;
        }

        $this->confirm = match (true) {
            $confirm instanceof Confirmable => $confirm,
            !!$confirm => Confirmable::make(),
            default => null,
        };
    }

    public function isNotConfirmable(): bool
    {
        return is_null($this->confirm);
    }

    public function isConfirmable(): bool
    {
        return ! $this->isNotConfirmable();
    }

    public function getConfirm(): ?Confirmable
    {
        if (! $this->isConfirmable()) {
            $this->evaluateConfirmAttribute();
        }

        return $this->confirm;
    }

    protected function evaluateConfirmAttribute(): void
    {
        $reflection = new ReflectionClass($this);
        $attributes = $reflection->getAttributes(Confirmable::class);

        if (!empty($attributes)) {
            $confirm = $attributes[0]->newInstance();
            $this->setConfirm($confirm);
        }
    }
}