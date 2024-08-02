<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns;

use Closure;

trait IsInline
{
    protected bool|Closure $inline = false;

    public function inline(bool|Closure $inline = true): static
    {
        $this->setInline($inline);

        return $this;
    }

    public function setInline(bool|Closure|null $inline): void
    {
        if (is_null($inline)) {
            return;
        }
        $this->inline = $inline;
    }

    public function isInline(): bool
    {
        return $this->evaluate($this->inline);
    }

    public function isNotInline(): bool
    {
        return ! $this->isInline();
    }
}
