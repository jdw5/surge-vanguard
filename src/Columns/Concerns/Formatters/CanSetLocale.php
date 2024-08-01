<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait CanSetLocale
{
    protected string|Closure|null $locale = null;

    protected function setLocale(string|Closure|null $locale): void
    {
        if (is_null($locale)) {
            return;
        }
        $this->locale = $locale;
    }

    protected function hasLocale(): bool
    {
        return ! $this->lacksLocale();
    }

    protected function lacksLocale(): bool
    {
        return is_null($this->locale);
    }

    protected function getLocale(): string
    {
        return $this->evaluate($this->locale) ?? app()->getLocale();
    }
}
