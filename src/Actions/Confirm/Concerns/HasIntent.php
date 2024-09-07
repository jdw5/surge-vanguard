<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Confirm\Concerns;

use Closure;
use Conquest\Table\Actions\Confirm\Enums\Intent;

trait HasIntent
{
    protected string|Closure|null $intent = null;

    public function intent(Intent|string|Closure $intent): static
    {
        $this->setIntent($intent);

        return $this;
    }

    public function setIntent(Intent|string|Closure|null $intent): void
    {
        if (is_null($intent)) {
            return;
        }
        $this->intent = $intent instanceof Intent ? $intent->value : $intent;
    }

    public function hasIntent(): bool
    {
        return ! $this->lacksIntent();
    }

    public function lacksIntent(): bool
    {
        return is_null($this->intent);
    }

    public function getIntent(): ?string
    {
        return $this->evaluate($this->intent);
    }

    public function constructive(): static
    {
        return $this->intent(Intent::Constructive);
    }

    public function destructive(): static
    {
        return $this->intent(Intent::Destructive);
    }

    public function informative(): static
    {
        return $this->intent(Intent::Informative);
    }
}
