<?php

namespace Conquest\Table\Actions\Http\DTOs;

use Illuminate\Http\Request;

class BulkActionData extends ActionData
{
    public function __construct(
        string|int $table,
        string $name,
        string $type,
        public readonly bool $all,
        public readonly array $except,
        public readonly array $only,
    ) {
        parent::__construct($table, $name, $type);
    }

    public static function from(Request $request): static
    {
        return resolve(static::class, [
            'id' => $request->input('id'),
            'name' => $request->string('name'),
            'type' => $request->string('type'),
            'all' => $request->boolean('all'),
            'except' => $request->input('except', []),
            'only' => $request->input('only', []),
        ]);
    }
}
