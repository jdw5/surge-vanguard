<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsIncludable;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Table\Actions\Concerns\HasEndpoint;

abstract class BaseAction extends Primitive
{
    use HasLabel;
    use HasMetadata;
    use HasName;
    use IsIncludable;
    use HasEndpoint;

    final public function __construct(string $name)
    {
        $this->setName($name);
        $this->setLabel($this->labelise($name));
        $this->setUp();
    }

    public static function make(string $name): static
    {
        return resolve(static::class, compact('name'));
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
