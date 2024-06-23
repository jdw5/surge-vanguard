<?php

namespace Jdw5\Vanguard\Actions;

use Jdw5\Vanguard\Concerns\HasAuthorization;
use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsIncludable;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Table\Actions\Concerns\HasEndpoint;

abstract class BaseAction extends Primitive
{
    use HasLabel;
    use HasName;
    use HasMetadata;
    use HasAuthorization;
    use HasEndpoint;

    public function __construct(
        string $label, 
        string $name = null,
        \Closure|bool $authorize = null
    ) {
        $this->setLabel($label);
        $this->setName($name ?? str()->slug($label));
        $this->setAuthorize($authorize);
        
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    /**
     * Retrieve the action as an array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
        ];
    }

    /**
     * Serialize the action to JSON.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
