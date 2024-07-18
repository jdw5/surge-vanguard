<?php

namespace Conquest\Table\Concerns;

use Illuminate\Support\Collection;

trait HasRecords
{
    protected ?Collection $records = null;

    protected function setRecords(Collection $records): void
    {
        $this->records = $records;
    }

    public function getRecords(): ?Collection
    {
        if (! $this->hasRecords()) {
            return null;
        }

        return $this->records;
    }

    public function hasRecords(): bool
    {
        return ! is_null($this->records);
    }
}
