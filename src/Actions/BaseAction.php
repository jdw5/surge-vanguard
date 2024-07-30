<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMeta;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Primitive;

abstract class BaseAction extends Primitive
{
    use IsAuthorized;
    use HasName;
    use HasLabel;
    use HasMeta;
    use HasType;

    public function __construct(
        string $label, 
        string $name = null,
        Closure|bool $authorize = null,
        array $meta = [],
    ) {
        parent::__construct();
        $this->setLabel($label);
        $this->setName($name ?? $this->toName($label));
        $this->setAuthorize($authorize);
        $this->setMeta($meta);
    }

    /**
     * Retrieve the action as an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'meta' => $this->getMeta(),
            'type' => $this->getType(),
        ];
    }
}
