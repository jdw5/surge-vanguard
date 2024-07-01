<?php

namespace Conquest\Table\Refiners;

use Closure;
use Conquest\Core\Concerns\CanAuthorize;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMetadata;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\IsActive;
use Conquest\Core\Primitive;

abstract class Refiner extends Primitive
{
    use CanAuthorize;
    use HasLabel;
    use HasMetadata;
    use HasName;
    use HasProperty;
    use HasType;
    use IsActive;

    public function __construct(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
    ) {
        $this->setProperty($property);
        $this->setName($name ?? $this->toName($property));
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        $this->setAuthorize($authorize);
    }

    /**
     * Convert the refinement to an array representation
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
        ];
    }
}
