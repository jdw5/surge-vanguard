<?php

namespace Jdw5\Vanguard\Refining;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasType;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsIncludable;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\Configurable;
use Jdw5\Vanguard\Refining\Concerns\HasValue;
use Jdw5\Vanguard\Refining\Contracts\Refines;
use Jdw5\Vanguard\Refining\Concerns\HasProperty;

abstract class Refinement extends Primitive implements Refines
{
    use HasProperty;
    use HasName;
    use HasLabel;
    use HasMetadata;
    use HasType;
    use HasValue;
    use Configurable;
    use IsIncludable;

    public function __construct(string $property, ?string $name = null) {
        $this->setProperty($property);
        $this->setName(str($name ?? $property)->replace('.', '_'));
        $this->setLabel($this->labelise($this->getName()));
        $this->configure();
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