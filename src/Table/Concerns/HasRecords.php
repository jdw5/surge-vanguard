<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Support\Collection;

trait HasRecords
{
    protected mixed $records = null;

    /**
     * Get the records for the table, if they have not been set, set them.
     * 
     * @return mixed
     */
    abstract public function getRecords(): Collection;

    /**
     * Get the first record from the table.
     * 
     * @return mixed
     */
    public function getFirstRecord(): mixed
    {
        return $this->getRecords()->first();
    }

    /**
     * Set the records for the table.
     * 
     * @param array $records
     */
    protected function setRecords(array $records): void
    {
        $this->records = $records;
    }
}