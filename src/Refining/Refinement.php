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
use Jdw5\Vanguard\Refining\Concerns\HasDefault;
use Jdw5\Vanguard\Refining\Concerns\HasProperty;

abstract class Refinement extends Primitive implements Refines
{
    use HasProperty;
    use HasName;
    use HasLabel;
    use HasMetadata;
    use HasType;
    use HasValue;
    use HasDefault;
    use Configurable;
    use IsIncludable;

    public function __construct(string $property, ?string $name = null) {
        $this->property($property);
        $this->name(str($name ?? $property)->replace('.', '_'));
        $this->label(str($this->getName())->headline()->lower()->ucfirst());

        $this->configure();
    }

    public function isActive(): bool
    {
        return !is_null($this->getValue());
    }
    
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
        ];
    }
}