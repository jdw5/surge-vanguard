<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Primitive;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMetadata;
use Conquest\Core\Concerns\CanAuthorize;
use Conquest\Core\Concerns\HasType;
use Conquest\Table\Actions\Concerns\HasEndpoint;

abstract class BaseAction extends Primitive
{
    use HasLabel;
    use HasName;
    use HasMetadata;
    use CanAuthorize;
    use HasEndpoint;
    use HasType;

    public function __construct(
        string $label, 
        string $name = null,
        Closure|bool $authorize = null
    ) {
        $this->setLabel($label);
        $this->setName($name ?? $this->toName($label));
        $this->setAuthorize($authorize);
    }

    public static function make(
        string $label,
        string $name = null,
        Closure|bool $authorize = null
    ): static
    {
        return new static($name, $label, $authorize);
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
