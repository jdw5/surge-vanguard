<?php

namespace Conquest\Table\DataObjects;

use Illuminate\Http\Request;

final class ActionTypeData
{
    public function __construct(
        public readonly string $type,
        public readonly string $name
    ) {}

    public static function from(Request $request): static
    {
        return new self(
            type: $request->string('type'),
            name: $request->string('name')
        );
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
