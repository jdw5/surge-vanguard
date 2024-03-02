<?php

namespace Jdw5\SurgeVanguard\Table\Actions;

use Jdw5\SurgeVanguard\Primitive;
use Jdw5\SurgeVanguard\Concerns\HasName;
use Jdw5\SurgeVanguard\Concerns\HasLabel;
use Jdw5\SurgeVanguard\Concerns\IsHideable;
use Jdw5\SurgeVanguard\Concerns\HasMetadata;
use Jdw5\SurgeVanguard\Table\Actions\Concerns\HasEndpoint;

abstract class BaseAction extends Primitive
{
    use HasLabel;
    use HasMetadata;
    use HasName;
    use IsHideable;

    final public function __construct(string $name)
    {
        $this->name($name);
        $this->label(str($name)->headline()->lower()->ucfirst());
        $this->setUp();
    }

    public static function make(string $name): static
    {
        return resolve(static::class, ['name' => $name]);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'hidden' => $this->isHidden(),
        ];
    }
}
