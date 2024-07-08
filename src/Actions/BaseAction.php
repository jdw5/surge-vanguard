<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Primitive;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMetadata;
use Conquest\Core\Concerns\CanAuthorize;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Core\Concerns\HasType;
use Illuminate\Http\Request;

abstract class BaseAction extends Primitive
{
    use HasLabel;
    use HasName;
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
     * 
     * @return array
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
