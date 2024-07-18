<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\CanAuthorize;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMetadata;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Primitive;

abstract class BaseAction extends Primitive
{
    use CanAuthorize;
    use HasName;
    use HasHttpMethod;
    use HasLabel;
    use HasMetadata;
    use CanAuthorize;
    use HasType;

    public function __construct(
        string $label, 
        string $name = null,
        Closure|bool $authorize = null,
        array $metadata = [],
    ) {
        parent::__construct();
        $this->setLabel($label);
        $this->setName($name ?? $this->toName($label));
        $this->setAuthorize($authorize);
        $this->setMetadata($metadata);
    }

    /**
     * Retrieve the action as an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'type' => $this->getType(),
        ];
    }
}
