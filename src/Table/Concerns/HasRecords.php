<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Support\Collection;

trait HasRecords
{
    protected Collection $records;
    
    protected function setRecords(Collection $records): void
    {
        $this->records = $records;
    }

    public function getRecords(): ?Collection
    {
        if (!$this->hasRecords()) return null;
        
        return $this->records;
    }

    public function hasRecords(): bool
    {
        return isset($this->records) && $this->records->isNotEmpty();
    }
}