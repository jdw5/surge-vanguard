<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Confirm\Concerns;

use Closure;

trait HasCancel
{
    protected string|Closure|null $cancel = null;

    public function cancel(string|Closure $cancel): static
    {
        $this->setCancelText($cancel);

        return $this;
    }

    public function setCancelText(string|Closure|null $cancel): void
    {
        if (is_null($cancel)) {
            return;
        }
        $this->cancel = $cancel;
    }

    public function HasCancel(): bool
    {
        return ! $this->lacksCancelText();
    }

    public function lacksCancelText(): bool
    {
        return is_null($this->cancel);
    }

    public function getCancelText(): string
    {
        return $this->evaluate($this->cancel) ?? config('table.confirm.cancel', 'Cancel');
    }
}
