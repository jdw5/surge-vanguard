<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns;

use Closure;
use ReflectionClass;
use Conquest\Table\Actions\Confirm\Confirm as Confirmable;

trait CanBeConfirmable
{
    /**
     * @var \Conquest\Table\Actions\Confirm\Confirm|null
     */
    protected ?Confirmable $confirm = null;

    /**
     * Set the properties of the confirm
     * 
     * @param Closure|array $confirm
     * @return static
     */
    public function confirm(Closure|array $confirm): static
    {
        $this->setConfirm(true);
        
        if (is_array($confirm)) {
            $this->confirm->setState($confirm);
        } 
        
        if (is_callable($confirm)) {
            $confirm($this->confirm);
        }

        return $this;
    }

    /**
     * Enable a confirm instance.
     * 
     * @param \Conquest\Table\Actions\Confirm\Confirm|bool|null $confirm
     * @return void
     */
    public function setConfirm(Confirmable|bool|null $confirm): void
    {
        if (is_null($confirm)) {
            return;
        }

        $this->confirm ??= match (true) {
            $confirm instanceof Confirmable => $confirm,
            !!$confirm => Confirmable::make(),
            default => null,
        };
    }

    /**
     * Get the confirm instance.
     * 
     * @return \Conquest\Table\Actions\Confirm\Confirm|null
     */
    public function getConfirm(): ?Confirmable
    {
        if (! $this->isConfirmable()) {
            $this->evaluateConfirmAttribute();
        }

        return $this->confirm;
    }

    /**
     * @internal
     */
    protected function evaluateConfirmAttribute(): void
    {
        $reflection = new ReflectionClass($this);
        $attributes = $reflection->getAttributes(Confirmable::class);

        if (!empty($attributes)) {
            $confirm = $attributes[0]->newInstance();
            $this->setConfirm($confirm);
        }
    }

    /**
     * @internal
     */
    protected function isConfirmable(): bool
    {
        return !is_null($this->confirm);
    }
}