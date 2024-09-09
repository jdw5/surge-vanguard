<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMeta;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Core\Primitive;

abstract class BaseAction extends Primitive
{
    use HasLabel;
    use HasMeta;
    use HasName;
    use HasType;
    use IsAuthorized;

    public function __construct(string $label, string|Closure|null $name = null)
    {
        parent::__construct();
        $this->setLabel($label);
        $this->setName($name ?? $this->toName($label));
    }

    public static function make(string $label, string|Closure|null $name = null): static
    {
        return resolve(static::class, compact('label', 'name'));
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'meta' => $this->getMeta(),
        ];
    }
}
