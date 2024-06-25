<?php

namespace Jdw5\Vanguard\Options;

use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\IsActive;
use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasValue;

class Option extends Primitive
{
    use HasLabel;
    use HasValue;
    use HasMetadata;
    use IsActive;

    public function __construct(
        mixed $value, 
        string $label = null,
        array $metadata = null
    ) {         
        $this->setValue(str($value)->slug());
        $this->setLabel($label ?? $this->toLabel($value));
        $this->setMetadata($metadata);
    }
    
    /**
     * Create a new option.
     * 
     * @param mixed $value
     * @param string|null $label
     */
    public static function make(
        mixed $value, 
        string $label = null,
        array $metadata = null
    ): static {
        return new static($value, $label, $metadata);
    }

    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
        ];
    }

    /**
     * Convert the object to its JSON representation.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function hasValue(mixed $value, bool $multiple = false): bool
    {
        return $multiple ?
            in_array($this->getValue(), $value) : $this->getValue() === $value;
    }
}