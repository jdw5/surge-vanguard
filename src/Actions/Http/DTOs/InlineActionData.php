<?php

namespace Conquest\Table\Actions\Http\DTOs;

use Illuminate\Http\Request;

final class InlineActionData extends ActionData
{
    public function __construct(
        string|int $table,
        string $name,
        string $type,
        public readonly int|string $id,
    ) {
        parent::__construct($table, $name, $type);
    }

    public static function from(Request $request): static
    {
        return resolve(self::class, [
            'table' => $request->input('table'),
            'name' => $request->string('name'),
            'type' => $request->string('type'),
            'id' => $request->input('id'),
        ]);
    }
}
