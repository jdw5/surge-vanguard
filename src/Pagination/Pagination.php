<?php

namespace Jdw5\Vanguard\Pagination;

use Jdw5\Vanguard\Concerns\IsActive;
use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasValue;

class Pagination extends Primitive
{
    use HasValue;
    use IsActive;

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'active' => $this->isActive(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

}