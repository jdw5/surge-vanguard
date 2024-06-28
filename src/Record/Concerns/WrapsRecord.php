<?php

namespace Conquest\Table\Record\Concerns;

use Conquest\Table\Record\Record;

trait WrapsRecord
{
    /**
     * Wrap the given data in a record.
     * 
     * @param mixed $data
     * @return Record
     */
    public function wrapRecord(mixed $data): Record
    {
        return new Record($data);
    }
}