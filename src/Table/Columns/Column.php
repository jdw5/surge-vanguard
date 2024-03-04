<?php

namespace Jdw5\Vanguard\Table\Columns;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasType;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsHideable;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Table\Columns\Concerns\IsKey;
use Jdw5\Vanguard\Table\Columns\Concerns\HasSort;
use Jdw5\Vanguard\Table\Columns\Concerns\HasFallback;
use Jdw5\Vanguard\Table\Columns\Concerns\HasTransform;

class Column extends Primitive
{
    use HasLabel;
    use HasMetadata;
    use HasName;
    use HasType;
    use IsHideable;
    use HasTransform;
    use HasSort;
    use HasFallback;
    use IsKey;

    public function __construct(string $name)
    {
        $this->name($name);
        $this->label(str($name)->afterLast('.')->headline()->lower()->ucfirst());
        $this->type('text');
        $this->setUp();
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->getName(), // database column should be hidden from being serialized
            'type' => $this->getType(), // text etc
            'label' => $this->getLabel(), // Headlined display column
            'metadata' => $this->getMetadata(),
            'fallback' => $this->getFallback(),
            'has_sort' => $this->hasSort(),
            'active' => $this->isSorting(),
            'direction' => $this->getDirection(),
            'next_direction' => $this->getNextDirection(),
            'sort_field' => $this->getSortName(),
        ];
    }
}
