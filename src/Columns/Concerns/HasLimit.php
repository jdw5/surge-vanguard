<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait HasLimit
{
    protected int|Closure|null $limit = null;

    public function limit(int|Closure $limit): static
    {
        $this->setLimit($limit);

        return $this;
    }

    public function setLimit(int|Closure|null $limit): void
    {
        if (is_null($limit)) {
            return;
        }
        $this->limit = $limit;
    }

    public function hasLimit(): bool
    {
        return ! $this->lacksLimit();
    }

    public function lacksLimit(): bool
    {
        return is_null($this->limit);
    }

    public function getLimit(): ?int
    {
        return $this->evaluate(
            value: $this->limit,
        );
    }
}
