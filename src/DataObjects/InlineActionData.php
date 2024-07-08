<?php

namespace Conquest\Table\DataObjects;

use Illuminate\Http\Request;

final class InlineActionData
{
    public function __construct(
        public readonly int|string $id,
        public readonly string $type,
        public readonly string $name,
    ) {
    }

    public static function from(Request $request): static
    {
        return new static(
            name: $request->string('name'),
            type: $request->string('type'),
            id: $request->input('id'),
        );
    }
}