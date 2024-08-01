<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Carbon\Carbon;

trait FormatsSince
{
    protected bool $since = false;

    public function since(): static
    {
        $this->since = true;
        return $this;
    }
    
    public function isSince(): bool
    {
        return $this->since;
    }

    public function isNotSince(): bool
    {
        return  ! $this->isSince();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function formatSince(mixed $value): string
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
