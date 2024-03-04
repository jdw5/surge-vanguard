<?php

namespace Jdw5\Vanguard\Refining;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasType;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsHideable;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\Configurable;
use Jdw5\Vanguard\Refining\Concerns\HasValue;
use Jdw5\Vanguard\Refining\Contracts\Refines;
use Jdw5\Vanguard\Refining\Concerns\HasDefault;

abstract class Refinement extends Primitive implements Refines
{
    use HasName;
    use HasLabel;
    use HasMetadata;
    use IsHideable;
    use Configurable;
    use HasDefault;
    use HasType;
    use HasValue;

    public function __construct(
        protected string $property,
        protected ?string $alias = null
    ) {
        $this->name(str($alias ?? $property)->replace('.', '_'));
        $this->label(str($this->getName())->headline()->lower()->ucfirst());
        $this->configure();

        if (is_null($alias)) $this->alias = $property;   
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
            // 'type' => $this->getType(),
            'metadata' => $this->getMetadata(),
            'hidden' => $this->isHidden(),
            'default' => $this->getDefaultValue(),
            'active' => $this->isActive(),
        ];
    }
}