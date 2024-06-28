<?php

namespace Conquest\Table\Pagination;

use Conquest\Table\Concerns\IsActive;
use Conquest\Core\Primitive;
use Conquest\Table\Concerns\HasValue;

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