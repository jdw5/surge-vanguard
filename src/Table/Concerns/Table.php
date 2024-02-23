<?php

namespace Jdw5\SurgeTable\Table;

use Jdw5\SurgeTable\Table\Concerns\HasColumns;
use JsonSerializable;

abstract class Table implements JsonSerializable 
{
    use HasColumns;

    public static function make(array $data = []): static
    {
        return new static($data);
    }

    public function jsonSerialize(): array
    {
        return [
            'data' => [],
            'columns' => $this->getTableColumns(),
            

        ];
    }
    
}