<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasRecords
{
    protected mixed $records = null;

    /**
     * Get the records for the table, if they have not been set, set them.
     * 
     * @return mixed
     */
    
    abstract public function getRecords(): array;

    /**
     * Get the first record from the table.
     * 
     * @return mixed
     */
    public function getFirstRecord(): mixed
    {
        return $this->getRecords()->first();
    }
}