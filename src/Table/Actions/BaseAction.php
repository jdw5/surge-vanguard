<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsHideable;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Table\Actions\Concerns\HasEndpoint;

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
