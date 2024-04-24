<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsIncludable;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\SetsLabel;
use Jdw5\Vanguard\Table\Actions\Concerns\DependsOn;
use Jdw5\Vanguard\Table\Actions\Concerns\HasEndpoint;

abstract class BaseAction extends Primitive
{
    use HasLabel;
    use HasMetadata;
    use HasName;
    use IsIncludable;
    use HasEndpoint;
    use SetsLabel;

    final public function __construct(string $name)
    {
        $this->name($name);
        $this->label($this->nameToLabel($name));
        $this->setUp();
    }

    public static function make(string $name): static
    {
        return resolve(static::class, compact('name'));
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
