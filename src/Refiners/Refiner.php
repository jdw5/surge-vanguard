<?php

namespace Jdw5\Vanguard\Refiner;

use Closure;
use Jdw5\Vanguard\Concerns\HasAuthorization;
use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasType;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\IsActive;
use Jdw5\Vanguard\Concerns\HasProperty;

abstract class Refiner extends Primitive
{
    use HasProperty;
    use HasName;
    use HasLabel;
    use HasMetadata;
    use HasType;
    use HasAuthorization;
    use IsActive;

    public function __construct(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null, 
    ) {
        $this->setProperty($property);
        $this->setName($name ?? $this->toName($property));
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        $this->setAuthorize($authorize);
    }

    public function isActive(): bool
    {
        return ! \is_null($this->getValue());
    }

    /**
     * Convert the refinement to an array representation
     * 
     * @return array
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
    
    /**
     * Serialise the refinement to JSON
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}