<?php

namespace Jdw5\Vanguard\Table\Record\Concerns;

use Jdw5\Vanguard\Table\Record\Record;

trait WrapsRecord
{
    /**
     * Wrap the given data in a record.
     * 
     * @param array $data
     * @return Record
     */
    public function wrapRecord(array $data): Record
    {
        return new Record($data);
    }
}