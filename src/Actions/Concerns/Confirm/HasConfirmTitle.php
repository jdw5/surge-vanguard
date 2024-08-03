<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirm;

use Closure;

trait HasConfirmTitle
{
    protected string|Closure|null $confirmTitle = null;

    public function confirmTitle(string|Closure $confirmTitle): static
    {
        $this->setConfirmTitle($confirmTitle);

        return $this;
    }

    public function setConfirmTitle(string|Closure|null $confirmTitle): void
    {
        if (is_null($confirmTitle)) {
            return;
        }
        $this->confirmTitle = $confirmTitle;
    }

    public function hasConfirmTitle(): bool
    {
        return ! $this->lacksConfirmTitle();
    }

    public function lacksConfirmTitle(): bool
    {
        return is_null($this->confirmTitle);
    }

    public function getConfirmTitle(): string
    {
        return $this->evaluate($this->confirmTitle) ?? config('table.confirm.title', 'Confirm');
    }

    // public function resolveConfirmTitle(mixed $model): void
    // {
    //     $this->confirmTitle = $this->evaluate(
    //         value: $this->confirmTitle,
    //         model: $model,
    //     );
    // }
}
