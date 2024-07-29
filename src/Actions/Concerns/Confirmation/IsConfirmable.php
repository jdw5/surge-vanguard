<?php
declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

use Closure;

trait IsConfirmable
{
    protected bool|Closure $confirm = false;

    public function confirmable(bool|Closure $confirm = true): static
    {
        $this->setConfirm($confirm);
        return $this;
    }

    public function setConfirmable(bool|Closure|null $confirm): void
    {
        if (is_null($confirm)) return;
        $this->confirm = $confirm;
    }

    public function isConfirmable(): bool
    {
        return $this->evaluate($this->confirm);
    }

    public function isNotConfirmable(): bool
    {
        return !$this->isConfirmable();
    }
}