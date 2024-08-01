<?php

namespace Conquest\Table\DataObjects;

use Illuminate\Http\Request;

final class InlineActionData extends ActionData
{
    public function __construct(
        string $name,
        string $type,
        public readonly int|string $id,
    ) {
        parent::__construct(name: $name, type: $type);
    }

    public static function from(Request $request): static
    {
        return resolve(self::class, [
            'name' => $request->string('name'),
            'type' => $request->string('type'),
            'id' => $request->input('id'),
        ]);
    }

    public function getId(): int|string
    {
        return $this->id;
    }
}
