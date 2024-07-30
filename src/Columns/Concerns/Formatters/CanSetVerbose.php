<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait CanSetVerbose
{
    protected bool|Closure $verbose = false;

    protected function setVerbose(bool|Closure|null $verbose): void
    {
        if (is_null($verbose)) return;
        $this->verbose = $verbose;
    }

    protected function isVerbose(): bool
    {
        return (bool) $this->evaluate($this->verbose);
    }

    protected function lacksVerbose(): bool
    {
        return !$this->isVerbose();
    }
}