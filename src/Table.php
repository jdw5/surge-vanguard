<?php

namespace Jdw5\SurgeTable;

use JsonSerializable;

abstract class Table implements JsonSerializable 
{

    public static function make(array $data = []): static
    {
        return new static($data);
    }

    public function jsonSerialize(): array
    {
        return [

        ];
    }
    
}