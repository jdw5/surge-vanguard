<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

trait UsesTime
{
    protected string $timeFormat = 'Y-m-d H:i:s';

    public function timeFormat(string $format): static
    {
        $this->timeFormat = $format;

        return $this;
    }

    public function getTimeFormat(): string
    {
        return $this->timeFormat;
    }
}