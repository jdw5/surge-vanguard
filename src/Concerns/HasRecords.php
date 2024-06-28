<?php

namespace Conquest\Table\Concerns;

trait HasRecords
{
    protected array|null $records = null;
    
    protected function setRecords(array $records): void
    {
        $this->records = $records;
    }

    public function getRecords(): ?array
    {
        if (!$this->hasRecords()) return null;
        return $this->records;
    }

    public function hasRecords(): bool
    {
        return !is_null($this->records);
    }
}