<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns;

use Closure;
use Conquest\Table\Actions\Confirm\Confirm;

trait CanBeConfirmable
{
    protected Confirm|null $confirm = null;

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

    protected function setConfirm(bool|null $confirm): void
    {
        if (is_null($confirm)) {
            return;
        }

        $this->confirm ??= $confirm ? Confirm::make() : null;
    }

    public function isNotConfirmable(): bool
    {
        return is_null($this->confirm);
    }

    public function isConfirmable(): bool
    {
        return ! $this->isNotConfirmable();
    }
}